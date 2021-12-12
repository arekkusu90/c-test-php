<?php

namespace Clx\Controllers;

use Carbon\Carbon;
use Clx\Utils\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;
use UserService;

class UserController extends UserService
{

    protected $views;

    public function __construct(PhpRenderer $views)
    {
        $this->views = $views;
    }

    /**
     * Prende in input il path dell'immagine da ridimensionare,
     * se quest'ultima non è gia stata processata viene ridimensionata
     * a 100px di larghezza, mantenendo l'aspect ratio e viene salvata sul
     * filesystem aggiungendo "-resized" alla fine del nome del file.
     * Infine restituisce il path dell'immagine ridimensionata.
     *
     * @param $path - Il path dell'immagine da ridimensionare
     * @return string - Il nuovo path dell'immagine ridimensionata
     */
    protected function resizeImage($path)
    {
        $pathNew = explode('.', $path);
        $ext = array_pop($pathNew);
        array_push($pathNew, '-resized', '.', $ext);
        $pathNew = implode('', $pathNew);

        // Effettuiamo la conversione solo se non l'abbiamo già fatta in precedenza
        if (!file_exists($pathNew)) {
            $image = imagecreatefromjpeg($path);
            $image = imagescale($image, 100, -1);
            imagejpeg($image, $pathNew);
        }

        return $pathNew;
    }


    public function home(ServerRequestInterface $req, ResponseInterface $res, array $args)
    {
        $this->views->setLayout("layout.phtml");
        return $this->views->render($res, 'home.phtml');
    }


    public function showUserAction(ServerRequestInterface $req, ResponseInterface $res, array $args)
    {
        $userList = $this->getAllUsers();

        $payload = $req->getParsedBody();
        $pActive = $payload['active'] ?? '-1';
        $pFrom = $payload['from'] ?? '';
        $pTo = $payload['to'] ?? '';
        $pName = $payload['name'] ?? '';
        $pSurname = $payload['surname'] ?? '';
        $pView = $payload['view'] ?? 'table';

        // Filtriamo l'elenco degli utenti in base ai filtri passati in input
        if (in_array($pActive, ['0', '1'], true)) {
            $pActive = (int)$pActive;
            $userList = array_filter($userList, function ($user) use ($pActive) {
                return $user->active === $pActive;
            });
        }

        if (!empty($pFrom)) {
            $pFromDateTime = Carbon::createFromFormat('d/m/Y H:i', $pFrom);
            $userList = array_filter($userList, function ($user) use ($pFromDateTime) {
                $loginDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $user->last_login);
                return $loginDateTime->greaterThanOrEqualTo($pFromDateTime);
            });
        }

        if (!empty($pTo)) {
            $pToDateTime = Carbon::createFromFormat('d/m/Y H:i', $pTo);
            $userList = array_filter($userList, function ($user) use ($pToDateTime) {
                $loginDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $user->last_login);
                // Dal momento che il client non ci passa i secondi,
                // effettuiamo il confronto solo sui minuti.
                // In questo modo sel il client ci chiede di prendere tutti gli utenti che
                // hanno fatto login fino alle 18:00, noi prendiamo anche quelli che hanno fatto
                // login alle 18:00 e 40 secondi.
                $loginDateTime = $loginDateTime->startOfMinute();
                return $loginDateTime->lessThanOrEqualTo($pToDateTime);
            });
        }

        if (!empty($pName)) {
            $userList = array_filter($userList, function ($user) use ($pName) {
                return Str::startsWith($user->name, $pName);
            });
        }

        if (!empty($pSurname)) {
            $userList = array_filter($userList, function ($user) use ($pSurname) {
                return Str::startsWith($user->surname, $pSurname);
            });
        }

        $userList = array_values($userList);

        // Ridimensionare le immagini
        foreach ($userList as $user) {
            $user->picture = $this->resizeImage($user->picture);
        }

        // Selezioniamo la view in base al parametro passato in input
        $view = 'userListTable.phtml';
        if ($pView === 'thumb') $view = 'userListCard.phtml';

        // Renderizziamo la view passandogli il contesto necessario
        $ctx = ['userList' => $userList];
        $this->views->setLayout("layout.phtml");
        return $this->views->render($res, $view, $ctx);
    }
}