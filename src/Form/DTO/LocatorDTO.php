<?php


namespace App\Form\DTO;


use Symfony\Component\Validator\Constraints as Assert;

final class LocatorDTO
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Length(min="8", max="8")
     */
    private $locator;

    /**
     * @return string|null
     */
    public function getLocator(): ?string
    {
        return $this->locator;
    }

    /**
     * @param string|null $locator
     * @return LocatorDTO
     */
    public function setLocator(?string $locator): LocatorDTO
    {
        $this->locator = $locator;

        return $this;
    }
}
