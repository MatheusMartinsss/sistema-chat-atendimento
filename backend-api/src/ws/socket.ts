import { Server } from "socket.io";
import type { Server as HttpServer } from "http";
import { AppDataSource } from "../config/data-source";
import { Chat } from "../entities/chats";
import jwt from 'jsonwebtoken'
let io: Server | null = null;

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
                    name: 'admin',
                    email: payload.email
                }
            } else if (user) {
                socket.data.user = {
                    name: 'Client',
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

    io.on('connection', (socket) => {
        const tempMessages: string[] = []
        const chatId = String(socket.data.chatId)
        const user = socket.data.user
        
        socket.on('client_connect', async () => {
            socket.join(chatId)
            console.log('client conectado no chat ', chatId)

        })
        socket.on('take_chat', async () => {
            socket.join(chatId)
            io?.to(chatId).emit("agent_connect")

        })
        socket.on('client_message', (message) => {
            if (!message) return;
            console.log(`message client ${user.name} chat ${chatId}`)
            io?.to(chatId).emit("client_message", message)
        })

        socket.on('agent_message', (message) => {
            if (!message) return;
            console.log(`message agent ${user.name} chat ${chatId}`)
            io?.to(chatId).emit("agent_message", message)
        })


    })
    return io;
}
