<?php
namespace Infrastructure\Persistence;
use Domain\Repository\IFishRepository;
use Domain\Entity\Fish;
use \Doctrine\DBAL\DriverManager;
use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;
class FishRepository implements IFishRepository
{
	private static $pdo = null;
	private static $entityManager = null;

	public function all()
	{
		return $this->getRepository()->findAll();
	}

	public function fetch($id)
	{
		return $this->getRepository()->find($id);
	}

	public function store(Fish $fish)
	{
		$em = $this->getEntityManager();
		$em->persist($fish);
		$em->flush();
	}

	public function delete($id)
	{
		$fish = $this->fetch($id);
		if(!$fish)
			return null;

		$this->deleteImmediately($fish);
        return $fish;
	}

	public function deleteImmediately(Fish $fish)
	{
		$em = $this->getEntityManager();
		$em->remove($fish);
		$em->flush();
	}

	protected function getRepository()
	{
		return $this->getEntityManager()->getRepository('Domain\Entity\Fish');
	}

	protected function getEntityManager()
	{
		if(self::$entityManager == null) {
			$isDevMode = true;
			$xml = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xml';
	        $config = Setup::createXMLMetadataConfiguration(array($xml),$isDevMode);
	        $conn = DriverManager::getConnection(array('pdo' => $this->getPdo()),$config);
	        self::$entityManager = EntityManager::create($conn,$config);
		}
        return self::$entityManager; 
	}

	protected function getPdo()
	{
		if(self::$pdo == null) {
			self::$pdo = new \PDO(getenv("DB_DSN"), getenv('DB_USER'), getenv('DB_PASSWD'));
		}
		return self::$pdo;
	}
}