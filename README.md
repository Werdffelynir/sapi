# SAPI

## Системная утилита на PHP под Linux (Ubuntu)

### Установка 
```
cd /var/sapi
git clone https://github.com/Werdffelynir/sapi.git
ln sapi.php /usr/bin/sapi
chmod +x /usr/bin/sapi
```

### На борту 

- программа host для создания виртуальных хостов

```
sapi host [create|remove] [host_name]
```
