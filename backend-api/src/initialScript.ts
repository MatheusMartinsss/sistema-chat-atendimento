import bcrypt from "bcryptjs";
import { AppDataSource } from "./config/data-source";
import { Agents } from "./entities/agents";

export async function createInitial() {
    const repo = AppDataSource.getRepository(Agents);
    const hashed = await bcrypt.hash("secret", 10);
    const exists = await repo.findOne({ where: { email: 'agent@example.com' } })
    
    if (exists) return

    await repo.save(repo.create({ name: "Agente Demo", email: "agent@example.com", password: hashed }));
    console.log('created initial user')
}
