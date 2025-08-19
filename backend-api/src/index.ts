import "reflect-metadata";
import express from "express";
import cors from "cors";
import { AppDataSource } from "./config/data-source";
import router from "./routes";
import { createInitial } from "./initialScript";

const app = express();
app.use(cors());
app.use(express.json());

app.use("/api", router);

AppDataSource.initialize()
    .then(() => {
        console.log("✅ DB conectado");
        const PORT = process.env.PORT || 3001;
        app.listen(PORT, () => console.log(`🚀 API em http://localhost:${PORT}`));
        createInitial()
    })
    .catch((e) => console.error("❌ DB erro:", e));
