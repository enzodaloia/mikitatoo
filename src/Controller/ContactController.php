<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    #[Route('/send-message', name: 'app_send_message', methods: ['POST'])]
    public function send(Request $request, HttpClientInterface $httpClient): Response
    {
        // $token = $request->request->get('recaptcha_token');

        // if (!$token) {
        //     throw new \Exception('Token reCAPTCHA manquant');
        // }

        // $response = $httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
        //     'body' => [
        //         'secret' => $_ENV['RECAPTCHA_SECRET_KEY'],
        //         'response' => $token,
        //     ],
        // ]);

        // $data = $response->toArray();

        // if (!$data['success'] || $data['score'] < 0.5 || $data['action'] !== 'submit') {
        //     $this->addFlash('error', 'Échec de la vérification anti-spam.');
        //     return $this->redirectToRoute('app_contact');
        // }
        // dd($request->request->all());
        $nom = $request->request->get('nom');
        $sujet = $request->request->get('sujet');
        $interlocuteur = $request->request->get('interlocuteur');
        if($interlocuteur == 'Jason'){
            $interlocuteur = "colle_jason@outlook.com";
        }else{
            $interlocuteur = "mikitatouage@gmail.com";
        }
        $telephone = $request->request->get('telephone');
        $emailUser = $request->request->get('email');
        $messageUser = $request->request->get('message');

        // Construction du texte du mail
        $texteMail = "Vous avez reçu un nouveau message via le formulaire de contact :\n\n";

        if (!empty($nom)) {
            $texteMail .= "Nom : $nom\n";
        }
        $texteMail .= "Téléphone : $telephone\n";
        if (!empty($sujet)) {
            $texteMail .= "Sujet : $sujet\n";
        }
        $texteMail .= "Email : $emailUser\n\n";
        $texteMail .= "Message :\n$messageUser";

        $email = (new Email())
            ->from('contact@ginko-art-tattoo.fr')
            ->replyTo($emailUser)
            // ->to($interlocuteur)
            ->to('enzo73.daloia@gmail.com')
            ->subject('Nouveau message du site de tatouage')
            ->text($texteMail);

        // Utilisation directe du transport OVH pour être sûr que ça marche
        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $mailerDirect = new \Symfony\Component\Mailer\Mailer($transport);
        $mailerDirect->send($email);

        $this->addFlash('success', 'Votre message a bien été envoyé.');
        return $this->redirectToRoute('app_contact');
    }

    #[Route('/test-ovh-mail', name: 'app_test_ovh_mail')]
    public function testOvhMail(): Response
    {
        // Crée le mail de test
        $email = (new Email())
            ->from('contact@ginko-art-tattoo.fr')
            ->to('enzo73.daloia@gmail.com')
            ->subject('Test OVH SMTP')
            ->text("Ceci est un test d'envoi direct via SMTP OVH.");

        try {
            // Crée un transport OVH TLS port 587
            $transport = Transport::fromDsn('smtp://contact@ginko-art-tattoo.fr:Mikikaneko01140%21@smtp.mail.ovh.net:587?encryption=tls&auth_mode=login');
            $debugMailer = new \Symfony\Component\Mailer\Mailer($transport);

            $debugMailer->send($email);

            $this->addFlash('success', 'Test SMTP OVH réussi ! Mail envoyé.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors du test SMTP OVH : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_contact');
    }
}
