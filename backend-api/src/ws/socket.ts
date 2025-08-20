import { Server } from "socket.io";
import type { Server as HttpServer } from "http";
import { AppDataSource } from "../config/data-source";
import { Chat } from "../entities/chats";

let io: Server | null = null;

export function initSocket(HttpServer: HttpServer) {
    io = new Server(HttpServer, {
        cors: {
            origin: '*',
            methods: ['GET', 'POST']
        }
    })

    io.on('connection', (socket) => {
        const tempMessages: string[] = []
        socket.on('client_connect', async (chatId: number) => {
            const repo = AppDataSource.getRepository(Chat)
            const chat = await repo.findOne({
                where: {
                    id: chatId
                }
            })

            if (!chat) return

            socket.join(room(chat.id))

        })
        socket.on('take_chat', async (chatId: number) => {
            const repo = AppDataSource.getRepository(Chat)

            const chat = await repo.findOne({
                where: {
                    id: chatId
                }
            })

            if (!chat) return
        })
        socket.on('client_message', async (payload: { chatId: number; message: string }) => {
            if (!payload?.chatId || !payload?.message) return;
            console.log('client message', payload.message)
            tempMessages.push(payload.message)
            io?.to(room(payload.chatId)).emit("client_message", payload.message)
        })

        socket.on('agent_message', (payload: { chatId: number; message: string }) => {
            if (!payload?.chatId || !payload?.message) return;
            console.log('agent message', payload.message)
            tempMessages.push(payload.message)
            io?.to(room(payload.chatId)).emit("agent_message", payload.message)
        })


    })


    return io;
}
function room(chatId: string | number) {
    return `chat:${chatId}`;
}