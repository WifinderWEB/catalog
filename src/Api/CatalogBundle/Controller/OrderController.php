<?php

namespace Api\CatalogBundle\Controller;

use Catalog\CatalogBundle\Entity\Catalog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Catalog\CatalogBundle\Entity\Repository\CatalogRepository;
use Symfony\Component\HttpFoundation\Request;
use Catalog\OrderBundle\Form\OrderType;
use Catalog\OrderBundle\Entity\Order;

class OrderController extends Controller {
    public function createAction($project_id, Request $request){
        $entity = new Order();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            foreach($entity->getGoods() as $one){
                $one->setOrder($entity);
            }
            $em->persist($entity);
            $em->flush();

            $this->sendOrder($entity);
            $this->sendOrderConfirm($entity);

            $result = array(
                'result' => 'ok',
                'order'  => $entity->getId()
            );
            return new JsonResponse($result);
        }

        $result = array(
            'result' => 'error',
            'message' => $form->getErrorsAsString()
        );

        return new JsonResponse($result);
    }

    private function createCreateForm($entity)
    {
        $form = $this->createForm(new OrderType(), $entity);

        return $form;
    }

    private function sendOrder(\Catalog\OrderBundle\Entity\Order $entity){
        $mailer = $this->container->get('mailer');

        $body = $this->getBodyMessage($entity);

        $message = \Swift_Message::newInstance()
            ->setSubject("Новый заказ")
            ->setContentType('text/html')
            ->setFrom($this->container->getParameter('mailer_mail_from'))
            ->setTo($this->container->getParameter('mailer_mail_to'))
            ->setBody($body);
        $mailer->send($message);
    }

    private function getBodyMessage(\Catalog\OrderBundle\Entity\Order $entity){
        $body = "<h1>Заказ №" . $entity->getId() .'" от ' . $entity->getCreated()->format('d.m.Y H:i:s') . '</h1>'.
        "<p>
            <b>Пользователь: </b> ".$entity->getUser()->getFullName() . "<br/>
            <b>Телефон: </b> " . $entity->getUser()->getPhone(). "<br/>
            <b>Email: </b> " . $entity->getUser()->getEmail(). "<br/>
            <b>Адрес: </b> " . $entity->getAddress() . "<br/>
        </p>
        <p>
            <b>Сумма заказа: </b> " . $entity->getItog() . "<br/>
            <b>Скидка: </b> " .$entity->getDiscount() ? $entity->getDiscount() : 0 . "%<br/>
        </p>
        <p>
            <b>Товары:</b><br/>
            <ol>";
        foreach($entity->getGoods() as $one){
            $body .= "<li>
                ID [" .$one->getGoodsId(). "] " .$one->getTitle(). "
            </li>";
        }
        $body .= "</ol></p>";

        return $body;
    }

    private function sendOrderConfirm(\Catalog\OrderBundle\Entity\Order $entity){
        $mailer = $this->container->get('mailer');

        $body = $this->getBodyConfirmMessage($entity);

        $message = \Swift_Message::newInstance()
            ->setSubject("Новый заказ")
            ->setContentType('text/html')
            ->setFrom($this->container->getParameter('mailer_mail_from'))
            ->setTo($entity->getUser()->getEmail())
            ->setBody($body);
        $mailer->send($message);
    }

    private function getBodyConfirmMessage(\Catalog\OrderBundle\Entity\Order $entity){
        $body = "<h1>Заказ №" . $entity->getId() .'" от ' . $entity->getCreated()->format('d.m.Y H:i:s') . '</h1>'.
        "<p>
            Ваш заказ успешно создан. В ближайшее время с Вами свяжется наш оператор.
        </p>
        <p>
            <b>Пользователь: </b> ".$entity->getUser()->getFullName() . "<br/>
            <b>Телефон: </b> " . $entity->getUser()->getPhone(). "<br/>
            <b>Email: </b> " . $entity->getUser()->getEmail(). "<br/>
            <b>Адрес: </b> " . $entity->getAddress() . "<br/>
        </p>
        <p>
            <b>Сумма заказа: </b> " . $entity->getItog() . "<br/>
            <b>Скидка: </b> " .$entity->getDiscount() ? $entity->getDiscount() : 0 . "%<br/>
        </p>
        <p>
            <b>Товары:</b><br/>
            <ol>";
        foreach($entity->getGoods() as $one){
            $body .= "<li>
                ID [" .$one->getGoodsId(). "] " .$one->getTitle(). "
            </li>";
        }
        $body .= "</ol></p>";

        return $body;
    }
}