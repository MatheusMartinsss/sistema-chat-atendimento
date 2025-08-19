import { Router } from 'express'
import { AuthController } from '../controllers/AuthController'

const router = Router();

router.post("/auth/login", AuthController.login)
router.get('/auth/me', AuthController.me)

export default router;