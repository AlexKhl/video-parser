# Video parser project

This is a PHP project which allows to find videos on any page and downloads them for making uploading to other services

## Installation

Use the [composer](https://getcomposer.org/) to install needed libs:

```bash
composer update
```
And it will download any necessary libs to the project

## Usage

Copy the example.destinations.json file and paste it at the same folder with renaming to destinations.json.
This file has the following json format: 
```json
{
  "Dailymotion": {
    "url": "dailymotion.com",
    "apiKey": "<YOUR_API_KEY>",
    "apiSecret": "<YOUR_API_SECRET>",
    "user": "<YOUR_LOGIN>",
    "password": "<YOUR_PASSWORD>"
  }
}
```
The main detail is a name of each element here it's <b><i>Dailymotion</i></b> because this name should be matched with the implemented class.
This file can take any other needed keys and values depending on destination video service, this list can has credentials for the connection and any other information for using of service.

## Run

For running the project from CLI just use the next model:
```shell script
php start.php http://any-link.com/for-the-following-parsing-of-the-yt-videos
```

## Classes creation
The folder /src/destinations has the main class <b><i>Destination</i></b>, any other destination class should be extended from it, and can be changed for the particular destination service.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.
Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)