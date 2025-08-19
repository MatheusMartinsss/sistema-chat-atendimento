import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, ManyToOne, JoinColumn } from "typeorm";
import { Chat } from "./chats";

@Entity("messages")
export class Message {
    @PrimaryGeneratedColumn()
    id!: number;

    @Column({ type: "int" })
    chat_id!: number;

    @ManyToOne(() => Chat, (chat) => chat.messages, { onDelete: "CASCADE" })
    @JoinColumn({ name: "chat_id" })
    chat!: Chat;

    @Column({
        type: "enum",
        enum: ["client", "agent"],
    })
    sender_type!: "client" | "agent";

    @Column({
        type: "varchar",
        length: 100,
    })
    sender_name!: string;

    @Column({
        type: "text",
    })
    content!: string;

    @CreateDateColumn({ type: "timestamp" })
    created_at!: Date;
}