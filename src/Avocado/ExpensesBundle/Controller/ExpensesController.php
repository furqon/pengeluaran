<?php

namespace Avocado\ExpensesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Avocado\ExpensesBundle\Entity\Expenses;
use Avocado\ExpensesBundle\Form\Type\ExpensesType;

/**
 * Expenses controller.
 *
 */
class ExpensesController extends Controller
{
    /**
     * Lists all Expenses entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('ExpensesBundle:Expenses')->findAll();

        $entity = new Expenses();
        $form   = $this->createForm(new ExpensesType(), $entity);

        return $this->render('ExpensesBundle:Expenses:index.html.twig', array(
            'entities' => $entities,
            'form'   => $form->createView()
        ));
    }

    /**
     * Lists all Expenses entities with json.
     *
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('ExpensesBundle:Expenses')->findBy(array('userid'=>'1'), array('time'=>'DESC'));
        var_dump($entities->getId()); die;

        $test = $entities->getArrayResult();

        var_dump($test); die;

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery("SELECT time, amount FROM expenses WHERE userid=1");

        $myArray = $query->getArrayResult();

        echo "<pre>";
        print_r($myArray);
        echo '</pre>';
        die;
    }

    /**
     * Finds and displays a Expenses entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ExpensesBundle:Expenses')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Expenses entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ExpensesBundle:Expenses:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Expenses entity.
     *
     */
    public function newAction()
    {
        $entity = new Expenses();
        $form   = $this->createForm(new ExpensesType(), $entity);

        return $this->render('ExpensesBundle:Expenses:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Expenses entity.
     *
     */
    public function createAction()
    {
        $entity = new Expenses();
        $userid = $this->get('security.context')->getToken()->getUser()->getId();
        $form   = $this->createForm(new ExpensesType(), $entity);
        $form->bindRequest($this->getRequest());

        if ($form->isValid()) {
            $entity->setUserid($userid);
            $entity->setTime(new \DateTime);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return new Response(json_encode(array('result' => 'saved')), 201);
        } 
        return new Response(json_encode(array('result' => 'failed'), 412);
    }

    /**
     * Displays a form to edit an existing Expenses entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ExpensesBundle:Expenses')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Expenses entity.');
        }

        $editForm = $this->createForm(new ExpensesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ExpensesBundle:Expenses:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Expenses entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ExpensesBundle:Expenses')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Expenses entity.');
        }

        $editForm   = $this->createForm(new ExpensesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('expenses_edit', array('id' => $id)));
        }

        return $this->render('ExpensesBundle:Expenses:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Expenses entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('ExpensesBundle:Expenses')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Expenses entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('expenses'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
