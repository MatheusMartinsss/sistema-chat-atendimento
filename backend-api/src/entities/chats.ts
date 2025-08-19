import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn, ManyToOne, JoinColumn, OneToMany } from "typeorm";
import { Agents } from "./agents";
import { Message } from "./messages";

@Entity("chats")
export class Chat {
    @PrimaryGeneratedColumn()
    id!: number;

    @Column({
        type: "varchar",
        length: 100,
    })
    client_name!: string;

    @Column({
        type: "varchar",
        length: 100,
    })
    client_email!: string;

    @Column({
        type: "enum",
        enum: ["waiting", "active", "closed"],
        default: "waiting",
    })
    status!: "waiting" | "active" | "closed";

    @Column({
        type: "varchar",
        length: 50,
        default: "website",
    })
    channel!: string;

    @Column({ type: "int", nullable: true })
    agent_id!: number | null;

    @ManyToOne(() => Agents, { nullable: true })
    @JoinColumn({ name: "agent_id" })
    agent?: Agents | null;

    @CreateDateColumn({ type: "timestamp" })
    created_at!: Date;

    @OneToMany(() => Message, (message) => message.chat)
    messages!: Message[];
}