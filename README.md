# Projekt---SI
Projekt z przedmiotu system interakcyjny wykonany w ramach drugiego roku studiów elektroniczne przetwarzanie informacji.

Temat: Serwis ogłoszeniowy.

Opis: Projekt ma służyć do wyświetlania i tworzenia ogłoszeń w serwisie. Umożliwia on założenie konta, logowanie, zmianę hasła i danych. Użytkownicy zalogowani dodatkowo mają możliwość tworzenia, edycji i usuwania swoich ogłoszeń. O wszystkich zmianach, dodawaniu i usuwaniu treści decyduje administrator. Możliwe jest również filtrowanie ogłoszeń po kategoriach i tagach.

Użytkownicy:

  • Niezalogowany
  
    o Rejestracja i logowanie
    
    o Wyświetlanie ogłoszeń na stronie (dla kategorii, tagów)
    
    o Wyświetlanie 10 najnowszych postów na stronie
    
  • Zalogowany
  
    o Wszystkie możliwości użytkownika niezalogowanego
    
    o Wylogowanie się
    
    o Dodanie kategorii
    
    o Zmiana danych, zmiana hasła
    
    o Zarządzanie swoimi ogłoszeniami (tworzenie, edycja, usuwanie)
    
  • Administrator
  
    o Wszystkie możliwości użytkownika zalogowanego
    
    o Tworzenie, edycja i usuwanie treści na stronie
    
    o Aktywacja ogłoszeń
    
    o Edycja i usuwanie kategorii
    
    o Zarządzanie kontami użytkowników (zmiana hasła, zmiana danych)
    
    o Edycja i usuwanie tagów
    
Projekt został stworzony z użyciem PHP, Twig.
Wykorzystano Symfony, Docker.

Autorką projektu jest Joanna Pajor.

Przykładowe dane do logowania:

• Użytkownik

    login: user1
    hasło: user1234
    
• Administrator

    login: admin0
    hasło: admin1234
