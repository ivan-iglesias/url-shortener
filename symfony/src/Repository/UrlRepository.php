<?php

namespace App\Repository;

use App\Entity\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Url|null find($id, $lockMode = null, $lockVersion = null)
 * @method Url|null findOneBy(array $criteria, array $orderBy = null)
 * @method Url[]    findAll()
 * @method Url[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Url::class);
    }

    /**
     * Devuelvo todas las URLs con sus estadisticas asociadas.
     */
    public function findAllWithStatistics(string $orderBy = null): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
                u.id,
                u.namelong,
                u.nameshort,
                total,
                smartphone as total_smartphone,
                computer as total_computer,
                last_hour
            FROM urlshortener.urls AS u
            LEFT JOIN (
                SELECT url_id, count(*) AS total FROM urlshortener.activities
                GROUP BY url_id
            ) AS t1 ON u.id = t1.url_id
            LEFT JOIN (
                SELECT url_id, count(*) AS last_hour FROM urlshortener.activities
                WHERE created_at > (NOW() - INTERVAL 24 HOUR)
                GROUP BY url_id
            ) AS t2 ON u.id = t2.url_id
            LEFT JOIN (
                SELECT
                    url_id,
                    GROUP_CONCAT(if(device = "smartphone", number_actions, NULL)) AS `smartphone`,
                    GROUP_CONCAT(if(device = "computer", number_actions, NULL)) AS `computer`
                FROM (
                    SELECT url_id, device, count(*) AS number_actions FROM urlshortener.activities
                    GROUP BY url_id, device
                ) as t GROUP BY url_id
        ) AS t3 ON u.id = t3.url_id';

        if (isset($orderBy)) {
            $sql .= " ORDER BY {$orderBy} DESC";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // /**
    //  * @return Url[] Returns an array of Url objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    */

    /*
    public function findOneBySomeField($value): ?Url
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
    */
}
