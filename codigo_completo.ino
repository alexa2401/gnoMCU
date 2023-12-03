#include <WiFi.h>
#include <HTTPClient.h>

#include <DHT.h> 
#define DHTPIN 5 //D19 
#define DHTTYPE DHT11 
#define LEDPIN 22
#define BUTTONPIN 23

DHT dht11(DHTPIN, DHTTYPE); 

String URL = "http://10.25.71.1/gnoberto/test_data.php";

const char* ssid = "tel_alex"; 
const char* password = "contrasena123"; 

float temperatura = 0;
float humedad = 0;
bool running = false;


void setup() {
  Serial.begin(9600);

  dht11.begin(); 
  pinMode(LEDPIN, OUTPUT);
  pinMode(BUTTONPIN, INPUT_PULLUP);
  
  connectWiFi();
  Serial.println("Presiona el boton para comenzar");
}

void loop() {
  if(WiFi.status() != WL_CONNECTED) {
    connectWiFi();
  }
  if(digitalRead(BUTTONPIN) == LOW){
    running = !running;
    delay(500);
    if(running){
      digitalWrite(LEDPIN, HIGH);
      delay(200);
      digitalWrite(LEDPIN, LOW);
      Serial.println("Iniciando Programa...");
    } else{
      for (int i = 0; i < 5; i++){
        digitalWrite(LEDPIN, HIGH);
        delay(200);
        digitalWrite(LEDPIN, LOW);
        delay(200);
      }
      Serial.println("Deteniendo programa...");
    }
  }

  if(running){
    Load_DHT11_Data();
    String postData = "&temperatura=" + String(temperatura) + "&humedad=" + String(humedad);
    HTTPClient http;
    http.begin(URL);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    int httpCode = http.POST(postData);
    String payload = http.getString();

    Serial.print("URL : "); Serial.println(URL); 
    Serial.print("Data: "); Serial.println(postData);
    Serial.print("httpCode: "); Serial.println(httpCode);
    Serial.print("payload : "); Serial.println(payload);
    Serial.println("--------------------------------------------------");
    delay(5000);
    if (humedad < 40 || humedad > 60 || temperatura < 18 || temperatura > 25){
      digitalWrite(LEDPIN, HIGH);
    } else {
      digitalWrite(LEDPIN, LOW);
    }
  }

}


void Load_DHT11_Data() {
  //-----------------------------------------------------------
  temperatura = dht11.readTemperature(); //Celsius
  humedad = dht11.readHumidity();
  //-----------------------------------------------------------
  // Check if any reads failed.
  if (isnan(temperatura) || isnan(humedad)) {
    Serial.println("Failed to read from DHT sensor!");
    temperatura = 0;
    humedad = 0;
  }
  //-----------------------------------------------------------
  Serial.printf("Temperature: %d °C\n", temperatura);
  Serial.printf("Humidity: %d %%\n", humedad);
}

void connectWiFi() {
  WiFi.mode(WIFI_OFF);
  delay(1000);
  //This line hides the viewing of ESP as wifi hotspot
  WiFi.mode(WIFI_STA);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi");
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
    
  Serial.print("connected to : "); Serial.println(ssid);
  Serial.print("IP address: "); Serial.println(WiFi.localIP());
}
