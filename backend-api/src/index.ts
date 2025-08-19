import express, { Request, Response } from "express";
import { AppDataSource } from "./config/data-source";
import "reflect-metadata";
const app = express();
const PORT = process.env.PORT || 8081;


app.use(express.json());


app.get("/", (req: Request, res: Response) => {
    res.send("Hello word");
});

app.listen(PORT, () => {
    console.log(`Servidor rodando em http://localhost:${PORT}`);
});

AppDataSource.initialize()
    .then(() => {
        console.log("📦 Banco conectado com sucesso!");

        app.listen(3000, () => {
            console.log("🚀 Servidor rodando na porta 3000");
        });
    })
    .catch((error) => console.error("Erro ao conectar no banco:", error));