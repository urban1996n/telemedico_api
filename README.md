# __ API na potrzeby rekrutacji dla Telemedi.co__

## API dla aplikacji klienckiej zamieszczonej pod adresem >https://github.com/urban1996n/telemedico_app
### Aplikacja zabezpieczona jest przez ApiKeyUserProvider, który jest domyślnym dostawcą zasobów użytkownika
### Firewall w postaci ApiKeyAuthenticator uniemożliwia korzystanie z API bez podania klucza(apiKey), który powinien być wysłany z każdym zapytaniem do niego, wyłączając ścieżki do logowania, rejestracji, oraz sprawdzenia dostępności nazwy użytkownika(na potrzeby rejestracji)
### Dodatkowo zabezpieczone są ścieżki dla kontroli administratora(z prefixem /users), oraz dla kontroli użytkownika(zmiana danych, oraz usuwanie konta użytkownika).
### Trzecim zabezpieczeniem dotyczącym zmiany i usuwania konta użytkownika(z wyłączeniem akcji przeprowadzonych przez administratora) jest metoda authenticate mikrousługi UserService.


