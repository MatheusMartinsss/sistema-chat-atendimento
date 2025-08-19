
import { Request, Response } from "express";
import { AppDataSource } from "../config/data-source";
import { Chat } from "../entities/chats";


export class ChatController {
    static async create(req: Request, res: Response) {
        const { email, name } = req.body as { email: string; name: string }

        if (!email || !name) {
            return res.status(422).json({ error: "Email e Nome são obrigatórios." });
        }

        const repo = AppDataSource.getRepository(Chat);
        const chat = repo.create({
            client_email: email,
            client_name: name,
            status: "waiting"
        })
        const savedChat = await repo.save(chat)
        return res.json(
            savedChat
        )
    }
    static async get(req: Request, res: Response) {
        const { id } = req.params
        const repo = AppDataSource.getRepository(Chat);
        const chat = await repo.findOne({ where: { id: Number(id) } });

        if (!chat) {
            return res.status(404).json({ error: "Chat não encontrado" });
        }

        return res.json(chat);
    }

    static async getAll(req: Request, res: Response) {
        const repo = AppDataSource.getRepository(Chat)
        const chat = await repo.find({})
        return res.json(chat)
    }
}