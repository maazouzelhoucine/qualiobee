<?php
// src/Controller/ForgotPasswordController.php
namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ForgotPasswordController extends AbstractController
{
    /**
     * @Route("/forgot-password", name="forgot_password", methods={"POST"})
     */
    public function requestReset(Request $request, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'])) {
            return new JsonResponse(['message' => 'Email required'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Generate a token and save it to the user
        $token = $tokenGenerator->generateToken();
        $user->setPasswordResetToken($token); // Add this field to the User entity

        $this->getDoctrine()->getManager()->flush();

        // Send reset email
        $email = (new Email())
            ->from('noreply@example.com')
            ->to($user->getEmail())
            ->subject('Password Reset Request')
            ->html("<p>Click <a href='http://localhost/reset-password/{$token}'>here</a> to reset your password.</p>");
        
        $mailer->send($email);

        return new JsonResponse(['message' => 'Password reset email sent']);
    }

    /**
     * @Route("/reset-password/{token}", name="reset_password", methods={"POST"})
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['password'])) {
            return new JsonResponse(['message' => 'Password required'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['passwordResetToken' => $token]);

        if (!$user) {
            return new JsonResponse(['message' => 'Invalid or expired token'], Response::HTTP_NOT_FOUND);
        }

        $user->setPassword($passwordEncoder->encodePassword($user, $data['password']));
        $user->setPasswordResetToken(null); // Clear the reset token after use
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['message' => 'Password successfully reset']);
    }
}
?>