import { Server } from "socket.io";
import type { Server as HttpServer } from "http";
import { AppDataSource } from "../config/data-source";
import { Chat } from "../entities/chats";
import jwt from 'jsonwebtoken'
import { Message } from "../entities/messages";
let io: Server | null = null;

interface IMessage {
    chat_id: number;
    sender_type: 'client' | 'agent';
    sender_name: string;
    content: string;
    createdAt?: Date;
}

export function initSocket(HttpServer: HttpServer) {
    io = new Server(HttpServer, {

        cors: {
            origin: '*',
            methods: ['GET', 'POST']
        }
    })
    io.use((socket, next) => {
        try {

            const token = socket.handshake.auth?.token
            const user = socket.handshake.auth?.user
            const chatId = socket.handshake.auth?.chatId
            if (token) {
                const payload = jwt.decode(token) as { id: string; email: string; name: string; }
                socket.data.user = {
                    id: payload?.id,
                    name: payload.name,
                    email: payload.email
                }
            } else if (user) {
                socket.data.user = {
                    id: user.id,
                    name: user.name,
                    email: user.email,
                }
            }
            if (chatId) {
                socket.data.chatId = chatId
            }
            return next();
        } catch (e) {
            return next(new Error('unauthorized'));
        }
    });

    let tempMessages: Record<string, IMessage[]> = {}
    io.on('connection', (socket) => {
        const chatId = String(socket.data.chatId)
        const user = socket.data.user
        socket.on('client_connect', async () => {
            socket.join(chatId)
            console.log('client conectado no chat ', chatId)

        })
        socket.on('take_chat', async () => {
            const chat = await getChatById(Number(chatId))
            if (chat && chat.agent_id !== null && chat.agent_id !== user.id) {
                throw new Error('Chat já possui um atendente!.')
            }
            updateChatAgent(Number(chatId), user.id)
            socket.join(chatId)
            io?.to(chatId).emit("agent_connect", { id: user.id, name: user.name })

        })
        socket.on('client_message', (message) => {
            if (!message) return;
            console.log(`message client ${user.name} chat ${chatId}`)
            addMessage({
                senderType: 'client',
                message: message,
                senderName: user.name
            })
            io?.to(chatId).emit("client_message", { message, name: user.name, id: user.id })
        })

        socket.on('agent_message', (message) => {
            if (!message) return;
            console.log(`message agent ${user.name} chat ${chatId}`)
            addMessage({
                senderType: 'agent',
                message: message,
                senderName: user.name
            })
            io?.to(chatId).emit("agent_message", { message, name: user.name, id: user.id })
        })

        socket.on('disconnect', () => {
            saveMessages()
        })

        function addMessage({ senderType, senderName, message }: { senderType: 'client' | 'agent'; senderName: string; message: string }) {
            if (!tempMessages[chatId]) {
                tempMessages[chatId] = [];
            }

            tempMessages[chatId].push({
                chat_id: Number(chatId),
                sender_type: senderType,
                sender_name: senderName,
                content: message
            })
        }

        async function saveMessages() {
            const query = AppDataSource.getRepository(Message)

            try {
                const messages = query.create(tempMessages[chatId])
                await query.save(messages)
                tempMessages[chatId] = []
            } catch (ex) {
                console.error(ex)
            }
        }
        async function getChatById(chatId: number) {
            if (!chatId) return
            const query = AppDataSource.getRepository(Chat)

            try {
                const chat = await query.findOne({
                    where: {
                        id: chatId
                    }
                })
                if (!chat) {
                    throw Error('Chat não encontrado!.')
                }
                return chat
            } catch (ex) {
                throw ex
            }
        }

        async function updateChatAgent(chatId: number, agentId: number) {
            if (!chatId) return
            const query = AppDataSource.getRepository(Chat)
            try {
                let chat = await query.findOne({
                    where: {
                        id: chatId
                    }
                })
                if (!chat) {
                    throw Error('Chat não encontrado!.')
                }
                chat.agent_id = agentId
                return await query.save(chat)
            } catch (ex) {
                throw ex
            }
        }

    })
    return io;
}
