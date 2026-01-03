<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/films', name: 'app_film_')]
class FilmController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $search = $request->query->get('search', '');
        $genre = $request->query->get('genre', '');
        
        $films = [
            ['id' => 1, 'titre' => 'Avengers Endgame', 'genre' => 'Action', 'duree' => 180, 'affiche' => 'avengers.jpg'],
            ['id' => 2, 'titre' => 'Inception', 'genre' => 'SF', 'duree' => 148, 'affiche' => 'inception.jpg'],
            ['id' => 3, 'titre' => 'Dune', 'genre' => 'SF', 'duree' => 155, 'affiche' => 'dune.jpg'],
            ['id' => 4, 'titre' => 'Oppenheimer', 'genre' => 'Drame', 'duree' => 180, 'affiche' => 'oppenheimer.jpg']
        ];

        // Filtrer les films
        $filmsFiltres = array_filter($films, function($film) use ($search, $genre) {
            return (empty($search) || stripos($film['titre'], $search) !== false) &&
                   (empty($genre) || $film['genre'] === $genre);
        });

        return $this->render('film/index.html.twig', [
            'films' => array_values($filmsFiltres),
            'search' => $search,
            'genres' => ['Action', 'SF', 'Drame', 'Comédie']
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(int $id): Response
    {
        $films = [
            1 => ['titre' => 'Avengers Endgame', 'duree' => 180, 'synopsis' => 'Les héros s\'unissent...'],
            2 => ['titre' => 'Inception', 'duree' => 148, 'synopsis' => 'Rêves dans des rêves...'],
            3 => ['titre' => 'Dune', 'duree' => 155, 'synopsis' => 'Désert et épices...']
        ];
        
        $film = $films[$id] ?? ['titre' => 'Film inconnu', 'duree' => 120];
        
        return $this->render('film/show.html.twig', ['film' => $film]);
    }
}
