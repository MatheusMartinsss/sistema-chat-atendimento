import { Entity, PrimaryGeneratedColumn, Column, CreateDateColumn } from "typeorm";

export enum status {
    ONLINE = 'online',
    OFFLINE = 'offline'
}

@Entity('agents')
export class Agents {
    @PrimaryGeneratedColumn()
    id: number;

    @Column({
        type: 'varchar',
        length: 100
    })
    name: string;

    @Column({
        type: 'varchar',
        length: 100,
        unique: true
    })
    email: string;

    @Column({
        type: 'varchar',
        length: 255
    })
    password: string

    @Column({
        enum: status,
        default: status.OFFLINE
    })
    status: status

    @CreateDateColumn()
    created_at: Date;
}