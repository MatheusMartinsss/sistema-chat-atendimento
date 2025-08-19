import { Request, Response, NextFunction } from "express";
import jwt from "jsonwebtoken";

export interface AuthPayload {
    id: number;
    email: string;
    name: string;
}

declare global {
    namespace Express {
        interface Request {
            user?: AuthPayload;
        }
    }
}

export function auth(required = true) {
    return (req: Request, res: Response, next: NextFunction) => {
        const authHeader = req.headers.authorization || "";
        const token = authHeader.startsWith("Bearer ") ? authHeader.slice(7) : "";

        if (!token) {
            if (!required) return next();
            return res.status(401).json({ error: "Missing token" });
        }

        try {
            const secret = process.env.JWT_SECRET || "dev-secret";
            const payload = jwt.verify(token, secret) as AuthPayload;
            req.user = payload;
            next();
        } catch {
            if (!required) return next();
            return res.status(401).json({ error: "Invalid token" });
        }
    };
}