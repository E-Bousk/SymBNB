<?php

namespace App\Entity;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use App\Repository\AdRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"title"}, message="Il existe déjà une annonce avec ce titre ({{ value }})")
 */
class Ad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 10, max = 255,
     *      minMessage = "Le titre doit faire au moins {{ limit }} caractères !",
     *      maxMessage = "Le titre ne peut pas faire plus de {{ limit }} caractères !")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min = 20,
     *      minMessage = "Le titre doit faire au moins {{ limit }} caractères !")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min = 100,
     *      minMessage = "Le titre doit faire au moins {{ limit }} caractères !")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message = "L'URL '{{ value }}' n'est pas une URL valide")
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="ad", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="ad")
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="ad", orphanRemoval=true)
     */
    private $comments;
    
    /**
     * Création du slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void 
     */
    public function initializeSlug()
    {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    /**
     * Permet de récupérer le commentaire d'un utilisateur par rapport à une annonce
     * 
     * @param User $booker 
     * @return mixed 
     */
    public function getCommentFromBooker(User $booker)
    {
        foreach ($this->comments as $comment) {
            if ($comment->getAuthor() === $booker) return $comment;
        }

        return null;
    }

    /**
     * Permet de caculer la moyenne des notations
     * 
     * @return float
     */
    public function getAverageRating() 
    {
        // 1) On transforme le tableau « array collection » (« $this->comment ») en 'vrai' tableau avec « toArray »
        // 2) On 'réduit' le tableau des commentaires (« $this->comment ») à une seule valeur avec « array_reduce » 
        // On boucle donc sur le tableau des commentaires et on appelle à chaque fois une fonction
        // en lui passant '$total' (qui commence à zéro) et le commentaire en lui-même dans lequel on récupère la note pour l'additionner à '$total'
        $sum = array_reduce($this->comments->toArray(), function($total, $comment) {
            return $total + $comment->getRating();
        }, 0);
        
        // Faire la division pour avoir la moyenne
        // (‼ éviter la division par zéro ‼)
        if (count($this->comments) > 0) return $sum / count($this->comments);

        // Si pas de commentaire
        return 0;
    }

    /**
     * Permet d'obtenir un tableau des jours qui ne sont pas disponibles
     * 
     * @return array Tableau d'objets DateTime représentant les jours d'occupation
     */
    public function getNotAvailableDays()
    {
        // crée un tableau de dates non disponibles
        $notAvailableDays = [];
        
        foreach ($this->bookings as $booking) {
            // Calcule les jours qui se trouvent entre la date d'arrivée et de départ
            // convertit en secondes (Timestamp)
            $result = range(
                $booking->getStartDate()->getTimestamp(),
                $booking->getEndDate()->getTimestamp(),
                24 * 60 * 60
            );

            // Transforme les données du tableau « $result » :
            // change chaque données (date au format timestamp) en véritable date (année-mois-jour)
            $days = array_map(
                function($dayTimestamp) {
                    return new \DateTime(date('Y-m-d', $dayTimestamp)); 
                },
                $result
            );
            
            // Ajoute les nouvelles dates au tableau de dates non disponibles
            $notAvailableDays = array_merge($notAvailableDays, $days);
        }

        // Retourne le tableau de dates non disponibles
        return $notAvailableDays;
    }

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAd($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAd($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAd($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAd() === $this) {
                $comment->setAd(null);
            }
        }

        return $this;
    }
}
