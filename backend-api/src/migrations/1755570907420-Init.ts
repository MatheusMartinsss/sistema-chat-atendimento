import { MigrationInterface, QueryRunner } from "typeorm";

export class Init1755570907420 implements MigrationInterface {
    name = 'Init1755570907420'

    public async up(queryRunner: QueryRunner): Promise<void> {
        await queryRunner.query(`CREATE TABLE \`agents\` (\`id\` int NOT NULL AUTO_INCREMENT, \`name\` varchar(100) NOT NULL, \`email\` varchar(100) NOT NULL, \`password\` varchar(255) NOT NULL, \`status\` enum ('online', 'offline') NOT NULL DEFAULT 'offline', \`created_at\` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6), UNIQUE INDEX \`IDX_5fdef501c63984b1b98abb1e68\` (\`email\`), PRIMARY KEY (\`id\`)) ENGINE=InnoDB`);
        await queryRunner.query(`CREATE TABLE \`chats\` (\`id\` int NOT NULL AUTO_INCREMENT, \`client_name\` varchar(100) NOT NULL, \`client_email\` varchar(100) NOT NULL, \`status\` enum ('waiting', 'active', 'closed') NOT NULL DEFAULT 'waiting', \`channel\` varchar(50) NOT NULL DEFAULT 'website', \`agent_id\` int NULL, \`created_at\` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6), PRIMARY KEY (\`id\`)) ENGINE=InnoDB`);
        await queryRunner.query(`CREATE TABLE \`messages\` (\`id\` int NOT NULL AUTO_INCREMENT, \`chat_id\` int NOT NULL, \`sender_type\` enum ('client', 'agent') NOT NULL, \`sender_name\` varchar(100) NOT NULL, \`content\` text NOT NULL, \`created_at\` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6), PRIMARY KEY (\`id\`)) ENGINE=InnoDB`);
        await queryRunner.query(`ALTER TABLE \`chats\` ADD CONSTRAINT \`FK_657cfb73c6daecaa183a84fc64b\` FOREIGN KEY (\`agent_id\`) REFERENCES \`agents\`(\`id\`) ON DELETE NO ACTION ON UPDATE NO ACTION`);
        await queryRunner.query(`ALTER TABLE \`messages\` ADD CONSTRAINT \`FK_7540635fef1922f0b156b9ef74f\` FOREIGN KEY (\`chat_id\`) REFERENCES \`chats\`(\`id\`) ON DELETE CASCADE ON UPDATE NO ACTION`);
    }

    public async down(queryRunner: QueryRunner): Promise<void> {
        await queryRunner.query(`ALTER TABLE \`messages\` DROP FOREIGN KEY \`FK_7540635fef1922f0b156b9ef74f\``);
        await queryRunner.query(`ALTER TABLE \`chats\` DROP FOREIGN KEY \`FK_657cfb73c6daecaa183a84fc64b\``);
        await queryRunner.query(`DROP TABLE \`messages\``);
        await queryRunner.query(`DROP TABLE \`chats\``);
        await queryRunner.query(`DROP INDEX \`IDX_5fdef501c63984b1b98abb1e68\` ON \`agents\``);
        await queryRunner.query(`DROP TABLE \`agents\``);
    }

}
