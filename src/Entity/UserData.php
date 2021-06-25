<?php
/**
 * UserData entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserData.
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserDataRepository")
 *
 * @ORM\Table(name="users_data")
 *
 * @UniqueEntity(fields={"email"})
 */
class UserData
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Firstname.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     *
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $firstname;

    /**
     * Lastname.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     *
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $lastname;

    /**
     * E-mail.
     *
     * @var string
     *
     * @Assert\Email
     *
     * @ORM\Column(
     *     type="string",
     *     length=180,
     *     unique=true,
     * )
     */
    private $email;

    /**
     * User.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="userdata", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * Getter for the Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Firstname.
     *
     * @return string|null Firstname
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * Setter for Firstname.
     *
     * @param string $firstname Firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * Getter for Lastname.
     *
     * @return string|null Lastname
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * Setter for Lastnam.
     *
     * @param string $lastname Lastnam
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * Getter for the E-mail.
     *
     * @return string|null E-mail
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for the E-mail.
     *
     * @param string $email E-mail
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter for the User.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for the User.
     *
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
