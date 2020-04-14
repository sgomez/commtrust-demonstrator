<?php


namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('email', null, [
                'show_filter' => true,
            ])
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('email', null, [
                'route' => ['name' => 'show']
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                ],
            ]);
        ;
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->tab('Applicant')
                ->with('show.metadata')
                    ->add('email')
                    ->add('attributes', null, [
                        'template' => '/admin/user/show_attributes.html.twig',
                    ])
                ->end()
                ->with('show.actions', [])
                    ->add('Actions', null, [
                        'template' => '/admin/user/mockup_actions.html.twig'
                    ])
                ->end()
            ->end()
            ->tab('Registry')
                ->with('Log')
                    ->add('Events', null, [
                        'template' => '/admin/user/mockup_events.html.twig'
                    ])
                ->end()
            ->end()
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }
}
