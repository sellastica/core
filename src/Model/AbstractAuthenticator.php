<?php
namespace Sellastica\Core\Model;

use Bazo;
use Nette;
use Sellastica\Entity\EntityManager;
use Sellastica\Entity\Mapping\IRepository;
use Sellastica\Identity\Model\IIdentity;

abstract class AbstractAuthenticator
{
	/** @var Nette\Security\User */
	protected $user;
	/** @var \Sellastica\Entity\Mapping\IRepository */
	protected $repository;
	/** @var Nette\Http\Session */
	protected $session;
	/** @var \Sellastica\Entity\EntityManager */
	protected $em;

	
	/**
	 * @param Nette\Security\User $user
	 * @param Nette\Http\Session $session
	 * @param IRepository $repository
	 * @param \Sellastica\Entity\EntityManager $em
	 */
	public function __construct(
		Nette\Security\User $user,
		Nette\Http\Session $session,
		IRepository $repository,
		EntityManager $em
	)
	{
		$this->user = $user;
		$this->session = $session;
		$this->repository = $repository;
		$this->em = $em;
	}

	/**
	 * @param array|Nette\Security\Identity $credentials
	 * @return \Sellastica\Entity\Entity\IEntity
	 * @throws Nette\InvalidArgumentException
	 */
	public function login($credentials)
	{
		if ($credentials instanceof Nette\Security\Identity) {
			$identity = $credentials;
		} elseif (is_array($credentials)) {
			$identity = $this->authenticate($credentials);
		} else {
			throw new Nette\InvalidArgumentException('Login method accepts credentials array or Identity object only');
		}

		if ($identity instanceof Nette\Security\Identity) {
			$this->user->login($identity);
			$this->user->setExpiration('+ 14 days');
			return $this->repository->find($identity->getId());
		}
	}

	/**
	 * Handles user logout
	 */
	public function logout()
	{
		$this->user->logout(true);
	}

	/**
	 * @param array $credentials
	 * @throws Nette\Security\AuthenticationException
	 * @return Nette\Security\Identity
	 */
	abstract public function authenticate(array $credentials);

	/**
	 * @param \Sellastica\Identity\Model\IIdentity $user
	 */
	protected function logInvalidLogin(IIdentity $user)
	{
		$user->addInvalidLogin();
		$this->em->persist($user);
	}

	/**
	 * @param IIdentity $user
	 */
	protected function logSuccessfullLogin(IIdentity $user)
	{
		$user->resetInvalidLogins();
		$this->em->persist($user);
	}
}
