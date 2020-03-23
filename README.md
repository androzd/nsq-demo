# nsq-demo

Добавьте 
```
extension="/path/to/nsq.so"
```
в `php.ini` файл

Для добавления сообщения в очередь запустите: 
```shell script
php artisan produce:message 
```

Для обработки очереди сообщений запустите:
```shell script
php artisan job:notifications
```

Для работы приложения нужны nsqd и nsqlookupd
Возможно понадобится изменить ip адреса

###### TODO: собрать докер
