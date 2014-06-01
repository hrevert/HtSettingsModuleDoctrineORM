<?php
namespace HtSettingsModuleDoctrineORM\Mapper;

use HtSettingsModule\Mapper\SettingsMapper as BaseSettingsMapper;
use Doctrine\ORM\EntityManager;
use HtSettingsModule\Options\DbOptionsInterface;
use HtSettingsModule\Entity\ParameterInterface;
use HtSettingsModule\Exception;

class SettingsMapper extends BaseSettingsMapper
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructor
     *
     * @param EntityManager            $em
     * @param DbOptionsInterface $options
     *
     * @return void
     */
    public function __construct(EntityManager $em, DbOptionsInterface $options)
    {
        $this->em       = $em;
        $this->options  = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function findByNamespace($namespace)
    {
        return $this->getRepository()->findBy(array('namespace' => $namespace));
    }
    
    /**
     * {@inheritDoc}
     */
    public function insertParameter(ParameterInterface $parameter)
    {
        $this->saveParameter($parameter);
    }
    
    /**
     * {@inheritDoc}
     */
    public function updateParameter(ParameterInterface $parameter)
    {
        if ($parameter->getId()) {
            $this->saveParameter($parameter);
        } else {
            $qb = $this->em->createQueryBuilder('s');
            $qb->update()->set('value = :value')
                ->where('s.namespace = :namespace AND s.name = :name')
                ->setParameter('namespace', $parameter->getNamespace())
                ->setParameter('name', $parameter->getName())
                ->setParameter('value', $parameter->getValue());
            return $qb->getQuery()->execute();
        }
    }

    /**
     * Saves a parameter
     *
     * @param ParameterInterface $parameter
     * @return void
     */    
    public function saveParameter(ParameterInterface $parameter)
    {
        $this->em->persist($parameter);
        $this->em->flush();        
    }
    
    /**
     * {@inheritDoc}
     */
    public function deleteParameter(ParameterInterface $parameter)
    {
        if ($parameter->getId()) {
            $this->em->remove($parameter);
            $this->em->flush();
        } else {
            $qb = $this->em->createQueryBuilder('s');
            $qb->delete()->where('s.namespace = :namespace AND s.name = :name')
                ->setParameter('namespace', $parameter->getNamespace())
                ->setParameter('name', $parameter->getName());
            return $qb->getQuery()->execute();
        }                
    }

    /**
     * {@inheritDoc}
     */
    public function findParameter($parameter, $name = null)
    {
        if ($parameter instanceof ParameterInterface) {
            $namespace = $parameter->getNamespace();
            $name = $parameter->getName();
        } elseif (is_string($parameter)) {
            $namespace = $parameter;
        } else {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects parameter 1 to be string or an instance of HtSettingsModule\Entity\ParameterInterface, %s provided instead',
                    __METHOD__,
                    is_object($parameter) ? get_class($parameter) : gettype($parameter)
                )
            );
        }
        $qb = $this->em->createQueryBuilder('s');
        $qb->select()->where('s.namespace = :namespace AND s.name = :name')
            ->setParameter('namespace', $namespace)
            ->setParameter('name', $name);

        return $qb->getQuery()->getSingleOrNullResult();                
    }
    
    protected function getRepository()
    {
        return $this->em->getRepository($this->options->getParameterEntityClass());
    }                    
}
