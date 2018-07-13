# Cloud.dk CLI

A cli for https://api.cloud.dk

### Usage
```
docker run -it -e API_KEY=SomeApiKey sazo/clouddk --help
```
```
docker run -it --env-file .env sazo/clouddk --help
```

Install ssh key
```
docker run \
    -it \
    -v ~/.ssh/id_rsa.pub:/root/.ssh/id_rsa.pub \
    -v ~/.ssh/id_rsa:/root/.ssh/id_rsa \
    --env-file .env sazo/clouddk secure:copy-ssh
```


