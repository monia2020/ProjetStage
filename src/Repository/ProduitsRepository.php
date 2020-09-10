<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Produits::class);
        
        $this->paginator = $paginator;
    }

    public function getLastInserted($entity, $number)
    {
        return $this->getEntityManager()
        ->createQuery(
            "SELECT e FROM $entity e ORDER BY e.id DESC"
        )
        ->setMaxResults($number)
        ->getResult();
    }

    /**
     * Récupere les produits en lien avec une recherche
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search)
    {
    
        $query = $this->getSearchQuery($search)->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            2
        );
    }

    
        /**
         * Récupere le prix minimuim et maximum correspondant à une recherche
        
         * @return integer[] 
         */

        public function findMinMax(SearchData $search) : array
        {
            $results = $this->getSearchQuery($search, true)
                ->select('MIN(p.prix) as min', 'MAX(p.prix) as max')
                ->getQuery()
                ->getScalarResult();
            return [(int)$results[0]['min'], (int)$results[0]['max']];
        }

        private function getSearchQuery(SearchData $search, $ignorePrix = false): ORMQueryBuilder
        {
            $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.categorie','c')
            ->select('sc', 'p')
            ->join('p.sousCategories','sc');
            
            

            if (!empty($search->q)) 
            {
                $query = $query
                    ->andWhere('p.nom LIKE :q')
                    ->setParameter('q', "%{$search->q}%");
            }

            if (!empty($search->min) && $ignorePrix === false) 
            {
                $query = $query
                    ->andWhere('p.prix >= :min')
                    ->setParameter('min', $search->min);
            }
    
            if (!empty($search->max) && $ignorePrix === false) 
            {
                $query = $query
                    ->andWhere('p.prix <= :max')
                    ->setParameter('max', $search->max);
            }

            if (!empty($search->promotion)) {
                $query = $query
                    ->andWhere('p.promotion = 1');
            }

            if(!empty($search->categorie))
            {
                $query = $query
                ->andWhere('c.id IN (:categorie)')
                ->setParameter('categorie', $search->categorie);
            }
    
            if (!empty($search->sousCategories)) 
            {
                $query = $query
                ->andWhere('sc.id IN (:sousCategories)')
                ->setParameter('sousCategories', $search->sousCategories);       
            }

            return $query;
        }

    // /**
    //  * @return Produits[] Returns an array of Produits objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produits
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
