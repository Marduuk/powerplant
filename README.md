####Rachunek sumienia:  
Pierwszą rzeczą od jakiej zacząłem było przemyślenie której bazy danych należy użyć. 
Pomimo braku doświadczenia z mongodb wiem ze ta baza służy do operacji na dużych zbiorach dlatego postanowiłem jej użyć.

  Nie zrobiłem narzędzia które miałoby generować raport co 24 godziny. 
  Z kontekstu zadania wywnioskowałem, że trigger odpalający komendę generującą raport, ma być zenkapsulowany w aplikacji.
  Nie udało mi się znaleźć odpowiedniego bundle'a lub biblioteki, jedna wymuszała użycie mysqla, a inne, dopisanie linijki co crontaba, co w przypadku całkowitej enkapsulacji nie wchodzi w grę.
  
Innym rozwiązaniem mógłby być daemon phpowy, jednakże tworzenie go dla taska, odpalanego co 24 godziny, jest słabym rozwiązaniem.
   Możliwymi innymi rozwiązaniami mogłyby być kolejki albo jakiś trigger wywołujący komendę z poziomu mongodb. 


  Nie byłem w stanie rozwiązać problemu z seedowaniem db. Próbowałem do tego użyć fikstur, ale wątpię, ze jest to wykonalne z uwagi na liczbę rekordów.
  Zrobiłem research na ten temat i nie znalazłem rozwiązania innego niż zmockowanie bazy danych.

  
  ####App:
  
   Samą aplikację starałem się napisać w sposób prosty i przejrzysty, wykorzystując dostarczone przez symfony narzędzia.

   #####Features:
   
  * Aplikacja odbiera po API pojedynczy raport, zapisuje go do bazy danych. 
  
  * Komenda ```GenerateDailyReport```tworzy raport zbiorczy z raportów z ostatniej doby i zapisuje go w postaci jsona
   w /src/logs/daily_report{timestamp}
   
  * W serwisie ```ReportService``` istnieje metoda za pomocą której możemy pobierać z bazy danych pojedyncze raporty na podstawie parametrów.
  
  
 
  Do aplikacji dodałem dwa testy, jeden sprawdzający zapis pojedynczego raportu, a drugi działanie 
metody służącej do wczytywania z bazy raportów cząstkowych.

  Postanowiłem także dodać prosty ```ExceptionController``` który ma służyć nie leakowaniu stacktrace'a do klienta.
  
 ##### Komendy 
  
  Przed odpaleniem apki należy wykonać:
  
  ```composer install```
  
  Oraz należy użyć fikstur na których opierają się testy (może zająć kilka minut...):
  
  ```php bin/console doctrine:mongodb:fixtures:load```
  
 
  Odpalenie servera:
  
  ```php bin/console server:start --port=4040```
  
  Komenda służąca do generowania dobowych raportów:
  
  ```php bin/console app:daily-report```
  
Raport jest generowany jako log w ``src/logs``.




Pozdrawiam serdecznie,
Kacper