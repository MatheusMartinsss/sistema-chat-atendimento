import { Request, Response } from "express";
import { AppDataSource } from "../config/data-source";
import { Agents } from "../entities/agents";
import bcrypt from "bcryptjs";
import jwt from 'jsonwebtoken'
export class AuthController {
    static async login(req: Request, res: Response) {
        const { email, password } = req.body as { email: string; password: string }

        if (!email || !password) {
            return res.status(422).json({ error: "Email e senha são obrigatórios." });
        }

        const repo = AppDataSource.getRepository(Agents);
        const agent = await repo.findOne({ where: { email } })
        if (!agent) return res.status(401).json({ error: 'Credenciais invalidas!.' })

        const ok = await bcrypt.compare(password, agent.password);
        if (!ok) return res.status(401).json({ error: "Credenciais inválidas." });

        const secret = process.env.JWT_SECRET || "dev"


        const token = jwt.sign({
            id: agent.id, email: agent.email, name: agent.name
        }, secret)

        return res.json({
            access_token: token,
            user: {
                id: agent.id,
                name: agent.name,
                email: agent.email
            }
        })
    }
    static async me(req: Request, res: Response) {
        return res.json({ user: req.user });
    }
}