<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/new-article", name="app_new_art")
     */
    public function newArticle(Request $req, EntityManagerInterface $em, CategoryRepository $cateRepo, ArticleRepository $articleRepository): Response
    {

        $article = new Article();
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($req);
        if ($articleForm->isSubmitted()) {
            foreach ($article->getCategories() as $val) {
                $cate = $cateRepo->find($val->getId());
                $article->addCategory($cate);
              $em->persist($val);
            }
            dump($cate);
            dump($article);
            //ESSAI A LA MAIN :
//          $cate1 = $cateRepo->find(3);
//          $article->addCategory($cate1);
            $em->persist($article);
            $em->flush();
        }

        return $this->render('main/new-article.html.twig', [
            "articleForm" => $articleForm->createView()
        ]);
    }
}
