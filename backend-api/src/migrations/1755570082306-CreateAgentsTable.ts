import { MigrationInterface, QueryRunner } from "typeorm";

export class CreateAgentsTable1755570082306 implements MigrationInterface {
    name = 'CreateAgentsTable1755570082306'

    public async up(queryRunner: QueryRunner): Promise<void> {
        await queryRunner.query(`CREATE TABLE \`agents\` (\`id\` int NOT NULL AUTO_INCREMENT, \`name\` varchar(100) NOT NULL, \`email\` varchar(100) NOT NULL, \`password\` varchar(255) NOT NULL, \`status\` varchar(255) ('online', 'offline') NOT NULL DEFAULT 'offline', \`created_at\` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6), UNIQUE INDEX \`IDX_5fdef501c63984b1b98abb1e68\` (\`email\`), PRIMARY KEY (\`id\`)) ENGINE=InnoDB`);
    }

    public async down(queryRunner: QueryRunner): Promise<void> {
        await queryRunner.query(`DROP INDEX \`IDX_5fdef501c63984b1b98abb1e68\` ON \`agents\``);
        await queryRunner.query(`DROP TABLE \`agents\``);
    }

}
