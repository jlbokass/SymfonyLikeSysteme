<?php

namespace App\Controller;

use App\Entity\LikedPost;
use App\Entity\Post;
use App\Repository\LikedPostRepository;
use App\Repository\PostRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'app_post')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
           'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/posts/{id}/like', name: 'app_post_like')]
    public function like(
        Post $post,
        EntityManagerInterface $manager,
        LikedPostRepository $likedPostRepository,
    ): JsonResponse
    {
        /** User $user */
        $user = $this->getUser();
        if (!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        // if the post is liked
        if ($post->isLikedByUser($user)) {
            $like = $likedPostRepository->findOneBy([
                'post' => $post,
                'user' => $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'The post is unliked',
                'likes' => $likedPostRepository->count(['post' => $post])
                ], 200);
        }

        // if the post is not liked
        $like = new LikedPost();
        $like
            ->setPost($post)
            ->setUser($user)
            ;

        $manager->persist($like);
        $manager->flush();


        return $this->json([
            'code' => 200,
            'message' => 'You have just liked this post',
            'likes' => $likedPostRepository->count(['post' => $post])
        ], 200);
    }
}
