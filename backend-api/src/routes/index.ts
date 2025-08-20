import { Router } from 'express'
import { AuthController } from '../controllers/AuthController'
import { ChatController } from '../controllers/ChatController';
import { auth } from '../middlewares/AuthMiddleware';

const router = Router();

router.post("/auth/login", AuthController.login)
router.get('/auth/me', AuthController.me)

router.post("/chats", ChatController.create)
router.get("/chat/:id", ChatController.get)
router.get('/chats',  ChatController.getAll  )

export default router;