<?php declare(strict_types = 1);

namespace SocialNews\Submission\Infrastructure;

use Doctrine\DBAL\Connection;
use SocialNews\Submission\Domain\Submission;
use SocialNews\Submission\Domain\SubmissionRepository;

final class DbalSubmissionRepository implements SubmissionRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    
    public function add(Submission $submission): void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->insert('submissions');
        $qb->values([
            'id' => $qb->createNamedParameter($submission->getId()->toString()),
            'author_user_id' => $qb->createNamedParameter($submission->getAuthorId()->toString()),
            'title' => $qb->createNamedParameter($submission->getTitle()),
            'url' => $qb->createNamedParameter($submission->getUrl()),
            'creation_date' => $qb->createNamedParameter(
                $submission->getCreationDate(),
                'datetime'
            ),
        ]);
        $qb->execute();
    }

    //Código vulnerável a ataques de SQL Injection
    public function addeEXEMPLO(Submission $submission): void
    {
        //INPUT
        //MY GITHUB', 'https://github.com/FranciscoMaier98', '2100-01-02 00:00:00'); /*
        //PRINT
        //INSERT INTO submissions (id, title, url, creation_date) VALUES( 'bcbca43f-859f-48b4-97fd-d25781c1caf7', 'MY GITHUB', 'https://github.com/FranciscoMaier98', '2100-01-02 00:00:00'); /*', '', '2023-12-30 20:35:32' );
        
        $this->connection->exec("
            INSERT INTO
                submissions (id, title, url, creation_date)
            VALUES(
                '{$submission->getId()->toString()}',
                '{$submission->getTitle()}',
                '{$submission->getUrl()}',
                '{$submission->getCreationDate()->format('Y-m-d H:i:s')}'
            );
        ");
    }
}