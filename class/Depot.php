<?php

require 'lib/function.php';

class Depot
{
    private $avail = array();
    private $price = 0;
    private $amount = 0;
    private $noOfPartsDeleted = 0;
    private $bricskOnStock = 0;


    public function countBrincOnStock()
    {

        $arr = $this->getAvail();

        $countBrincOnStock = 0;
        for ($i = 0 + $this->getNoOfPartsDeleted(); $i < count($this->getAvail()) + $this->getNoOfPartsDeleted(); $i++) {
            $countBrincOnStock += $arr[$i][0];
        }
        return $countBrincOnStock;

    }

    /**
     * @return int
     */
    public function getBricskOnStock(): int
    {
        return $this->bricskOnStock;
    }

    /**
     * @param int $bricskOnStock
     */
    public function setBricskOnStock(int $bricskOnStock): void
    {
        $this->bricskOnStock = $bricskOnStock;
    }

    /**
     * @return int
     */
    public function getNoOfPartsDeleted(): int
    {
        return $this->noOfPartsDeleted;
    }

    /**
     * @param int $noOfPartsDeleted
     */
    public function setNoOfPartsDeleted(int $noOfPartsDeleted): void
    {
        $this->noOfPartsDeleted = $noOfPartsDeleted;
    }

    // Store availability setter, getter, show

    public function getAvail(): array
    {
        return $this->avail;
    }

    public function setAvail(array $avail): void
    {
        $this->avail = $avail;
    }

    public function showAvail(): void
    {
        echo 'Stan składu: ';
        debug($this->getAvail());
        echo '<hr>';

    }

    // Price setter, getter, show

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function showPrice(): void
    {
        echo 'Cena zamówienia: ';
        echo $this->getPrice() . ' zł';
        echo '<hr>';
    }

    // Store details

    public function showStore($newItem): void
    {
        echo "Nowa partia: ";
        debug($newItem);
        echo '<hr>';
    }

    // Pull details

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    public function showPull($amount): void
    {
        echo "Zamówienie: ";
        echo $amount;
        echo '<hr>';
    }

    public function totalPrice($amount, $unitPrice)
    {
        $this->price = $amount * $unitPrice;
        return $this->price;

    }

    /*store - Dodaje nową partię cegieł do składu. Amount - ilość cegieł w partii, price - cena jednej cegły
    jak dodać element do tablicy

    1. pobiera aktualną zawartość tablicy metodą getAvail() i przypisuje do zmiennej $curAvail
    2. tworzy nowy element tablicy - $newItem - dwuelementową tablicę, gdzie na indexie zerowym jest ilość, a na na pierwszym cena
    3. dodaje nowy element tablicy do $curAvail na jej końcu funkcją array_push
    4. aktualizuje tablicę $curAvail przez setAvail

    */

    public function store(int $amount, float $price): void
    {
        $curAvail = $this->getAvail();
        $newItem = array($amount, $price);
        $this->showStore($newItem);
        array_push($curAvail, $newItem);
        $this->setAvail($curAvail);

    }

    /*

    0. Deklaruję $order, w której będzie przechowywany stan realizacji zamowienia dla procesu wydania żadanej ilości cegieł $amount
    1. pobiera aktualną zawartość tablicy metodą getAvail() i przypisuje do zmiennej $curAvail
    2. przechodzi do najstarszej dodanej partii cegieł - czyli pierwszego elementu tablicy
    3. sprawdza, czy na indeksie zerowym, wskazującym ilość cegieł jest żadana ilość cegieł
    4. jeśli jest:
        a) redukuje indeks zerowy o żadaną ilość cegieł
        b) zwraca wartość zakupu $cost
       jeśli nie jest:
        a) pobiera ilość w partii z indexu zerowego elementu tablicu
        b) pobiera cenę partii z indexu pierwszego elementu tablicy
        c) tworzy tablicę $fromThisPart, gdzie umieszcza iloczyn ilści i ceny
        d) usuwa partię - element tablicy
        e) redukuje zmienną $amount o liczebność oczyszczonej partii
        f) przechodzi do następnego elementu tablicy $curAvail do wyzerowania $amount
    */

    public function pull(int $amount): float

    {
        $this->setAmount($amount);
        $order = $this->getAmount();
        $this->setPrice(0);
        $curAvail = $this->getAvail();
        $this->showPull($amount);
        $noOfPartsDeleted = $this->getNoOfPartsDeleted();

        if ($this->countBrincOnStock() < $amount) {

            echo "<hr><strong> Brak wymaganej ilości cegieł na stanie</strong><hr> ";

        }

        while ($order > 0) {

            for ($i = 0 + $noOfPartsDeleted; $i < count($curAvail) + $noOfPartsDeleted; $i++) {

                $order = $this->getAmount();

                if ($curAvail[$i][0] > $order) {

                    $curAvail[$i][0] = $curAvail[$i][0] - $order;
                    $this->setPrice($this->getPrice() + $this->totalPrice($order, $curAvail[$i][1]));
                    $this->setAvail($curAvail);

                } elseif ($curAvail[$i][0] <= $order) {

                    $inThisPartAmount = $curAvail[$i][0];

                    // cenna dla tej częsci dostępnej partii:

                    $inThisPartPrice = $curAvail[$i][1];

                    /// cena za cegły z tej partii
                    $priceForBricsInParty = $this->getPrice() + $this->totalPrice($inThisPartAmount, $inThisPartPrice);

                    $this->totalPrice($inThisPartAmount, $curAvail[$i][1]);

                    $this->setPrice($priceForBricsInParty);

                    $this->getPrice();

                    // Usunięcie wyczerpanego składu

                    unset($curAvail[$i]);

                    $this->setAvail($curAvail);

                    $noOfPartsDeleted++;

                    $this->setNoOfPartsDeleted($noOfPartsDeleted);

                    $newAmount = $this->getAmount() - $inThisPartAmount;

                    $this->setAmount($newAmount);

                    $this->getAmount();

                }
            }

            return $this->getPrice();
        }

    }

}

