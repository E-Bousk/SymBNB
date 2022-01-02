<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="La date d'arrivée doit être au bon format")
     * @Assert\GreaterThan("today", message="La date d'arrivée doit être postérieure à la date d'aujourd'hui !")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="La date de départ doit être au bon format")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date de départ doit être postérieure à la date d'arrivée")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Ajout de la date de création de la réservation
     * et calcul du montant total
     * 
     * @ORM\PrePersist
     * 
     * @return void 
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        if (empty($this->amount)) {
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    // Calcul le nombre de jour réservés
    public function getDuration()
    {
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function isBookableDates()
    {
        // Récupère les jours non disponibles à une nouvelle réservation
        $notAvailableDays = $this->ad->getNotAvailableDays();

        // Récupère les jours demandés pour une nouvelle réservation
        $bookingDays = $this->getDays();

        // Transforme les tableaux de 'DateTime' en 'String' (pour les comparer facilement) :
        // a) Factorisation de la fonction qui transforme le 'DateTime' en 'String'
        $formatDay = function($dateTime) {
            return $dateTime->format('Y-m-d');
        };
        // b) Utilisation de cette fonction sur le tableau des jours non disponibles
        $naDays = array_map($formatDay, $notAvailableDays);
        // c) Utilisation de cette fonction sur le tableau des jours à vérifier (réservation demandée)
        $bDays = array_map($formatDay, $bookingDays);

        // Boucle sur les jours de la réservation pour voir si un de ces jours se trouve dans les jours non disponibles
        foreach ($bDays as $bDay) {
            if (in_array($bDay, $naDays)) return false;
        }

        return true;
    }

    /**
     * Permet de récupérer un tableau des journées qui correspondent à la réservation
     * 
     * @return array Tableau d'objets DateTime représentant les jours de la réservation
     */
    public function getDays()
    {
        // Calcule les jours qui se trouvent entre la date d'arrivée et de départ
        // convertit en secondes (Timestamp)
        $result = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 * 60 *60
        );

        // Transforme les données du tableau « $result » :
        // change chaque données (date au format timestamp) en véritable date (année-mois-jour)
        $days = array_map(
            function($dayTimestamp) {
                return new \DateTime(date('Y-m-d', $dayTimestamp));
            },
            $result
        );

        return $days;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
