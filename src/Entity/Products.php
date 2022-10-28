<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Ürün adı boş bırakılamaz')]
    #[Assert\Length(min: 3, minMessage: 'Ürün adı en az 3 karakter olmalıdır')]
    #[Assert\Length(max: 255, maxMessage: 'Ürün adı en fazla 255 karakter olmalıdır')]
    private ?string $name = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Ürün fiyatı boş bırakılamaz')]
    #[Assert\Regex(pattern: '/^[0-9]*$/', message: 'Ürün fiyatı sadece rakamlardan oluşmalıdır')]
    private ?string $price = null;



    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Ürün açıklaması boş bırakılamaz')]
    #[Assert\Length(min: 3, minMessage: 'Ürün açıklaması en az 3 karakter olmalıdır')]
    #[Assert\Length(max: 255, maxMessage: 'Ürün açıklaması en fazla 255 karakter olmalıdır')]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[Assert\NotBlank(message: 'Kategori seçimi boş bırakılamaz')]
    private ?Categories $categories = null;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: Images::class)]
    private Collection $images;



    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProducts($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProducts() === $this) {
                $image->setProducts(null);
            }
        }

        return $this;
    }
}
