// Järeltöötlus PHP-s

<?php
// JOIN päring
$stmt = $conn->prepare('
    SELECT contacts.id, contacts.name, phones.number
    FROM contacts
    LEFT JOIN phones ON contacts.id = phones.contact_id
    ORDER BY contacts.id
');

$stmt->execute();

// Järeltöötlus: grupeerime kontaktide kaupa
$contacts = [];

foreach ($stmt as $row) {
    $contactId = $row ['id'];

    // Kui kontakti veel pole, lisa see
    if (!isset($contacts[$contactId])) {
        $contacts[$contactId] = [
            'id'=> $row['id'],
            'name'=> $row['name'],
            'phones'=> []
        ];
    }

    // Lisa telefon (kui eksisteerib)
    if ($row['number'] !== null) {
        $contacts[$contactId]['phones'][] = $row['number'];
    }
}

// Kuvamine
foreach ($contacts as $contact) {
    echo $contact['name'] . ': ';
    echo implode(', ', $contact['phones'] . "\n");
}



//(Objektidega :
class Contact {
    public int $id;
    public string $name;
    public array $phones = [];// list telefone
    public function __construct(int$id, string $name) {
        $this->id = $id;
        $this ->name = $name;
        }
}

// JOIN päring + järeltöötlus
$stmt = $conn ->prepare('
    SELECT contacts.id, contacts.name, phones.number
    FROM contacts
    LEFT JOIN phones ON contacts.id = phones.contact_id
    ORDER BY contacts.id
');

$stmt->execute();

$contacts = [];

foreach ($stmt as $row) {
    $contactId = $row['id'];

    // Loo uus kontakt objekt, kui pole veel
    if (!isset($contacts[$contactId])) {
        $contacts[$contactId] = new Contact($row['id'], $row['name']);
    }

    // Lisa telefon
    if ($row['number'] !== null) {
        $contacts[$contactId]->phones[] = $row['number'];
    }
}

// Kuvamine vaates
foreach ($contacts as $contact) {
    echo "<h3> {$contact->name} </h3>";
    echo"<ul>";
        foreach($contact->phones as $phone) {
            echo "<li>{$phone}</li>";
        }
    echo "</ul>"; ;
}