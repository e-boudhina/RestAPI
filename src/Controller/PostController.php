<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function MongoDB\BSON\toJSON;

class PostController extends AbstractFOSRestController
{
    /**
     * @var PostRepository
     */
    private $postRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;

        $this->entityManager = $entityManager;
    }

    public function getPostsAction()
    {
        $data = $this->postRepository->findAll();
        return $this->view($data,Response::HTTP_OK);
    }

    public function getPostAction($id){

        $data = $this->postRepository->find($id);
        if ($data){
        return $this->view($data,Response::HTTP_OK);
        }
        return $this->view(['message'=>'There is no post registered under the id '.$id],Response::HTTP_NOT_FOUND);

    }

    /**
     * @Rest\RequestParam(name="title",description="Title of the post" ,nullable=false)
     * @Rest\RequestParam(name="description",description="Content of the post" ,nullable=false)
     * @param ParamFetcher $paramFetcher
     * @return mixed
     */
    public function postPostAction(ParamFetcher $paramFetcher){
        $title = $paramFetcher->get('title');
        $description = $paramFetcher->get('description');

        if ($title&&$description){
            $post = new Post();
            $post->setTitle($title);
            $post->setDescription($description);
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            return $this->view($post,Response::HTTP_CREATED);
        }
         return $this->view(['title'=>'this cannot be null','description'=>'this cannot be null'],Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\RequestParam(name="title",description="Title of the post" ,nullable=true)
     * @Rest\RequestParam(name="description",description="Content of the post" ,nullable=true)
     * @param ParamFetcher $paramFetcher
     * @param $id
     * @return mixed
     */
    public function patchPostAction(ParamFetcher $paramFetcher,$id){
        $title = trim($paramFetcher->get('title'));
        $description = trim($paramFetcher->get('description'));


        $currentpost = $this->postRepository->find($id);
        if (!$currentpost){
            return $this->view(['message'=>'You can\'t update a post that does not exist'],Response::HTTP_NOT_FOUND);

        }elseif ($title=='' && $description==''){
            return $this->view(['message'=>'You must provide at least one the these two fields "title" or "description" '],Response::HTTP_BAD_REQUEST);
        }
        elseif ($currentpost&&$title&&$description) {
            $currentpost->setTitle($title);
            $currentpost->setDescription($description);
            $this->entityManager->persist($currentpost);
            $this->entityManager->flush();
            return $this->view(['message'=>'Post number '.$id.' has been Updated with success'],Response::HTTP_ACCEPTED);
        }
         elseif ($currentpost&&$title)
         {
             $currentpost->setTitle($title);
             $this->entityManager->persist($currentpost);
             $this->entityManager->flush();
             return $this->view(['message'=>'The title of Post number '.$id.' has been Updated with success','postinfo'=>$currentpost],Response::HTTP_ACCEPTED);
        }
        elseif ($currentpost&&$description){
            $currentpost->setDescription($description);
            $this->entityManager->persist($currentpost);
            $this->entityManager->flush();
            return $this->view(['message'=>'The description of Post number '.$id.' has been Updated with success','postInfo'=>$currentpost],Response::HTTP_ACCEPTED);
        }
        return $this->view(['message'=>'Something went wrong'],Response::HTTP_BAD_REQUEST);
    }


   public function deletePostAction($id)
   {
       $postid = $this->postRepository->find($id);
       if ($postid){
           $this->entityManager->remove($postid);
           $this->entityManager->flush();
           return $this->view(['message'=>'Post number '.$id.' has been DELETED with success'],Response::HTTP_OK);
       }
       return $this->view(['message'=>'There is no post registered under the id '.$id],Response::HTTP_NOT_FOUND);
   }
}
