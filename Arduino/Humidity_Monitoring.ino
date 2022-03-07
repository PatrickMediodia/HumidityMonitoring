#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include "DHTesp.h"
#include <time.h>

WiFiClient wifiClient;                                  //HTTP WiFiClient
HTTPClient http;                                        //HTTP client instance, you can have many
DHTesp dht;

const String SSID = "ZTE_2.4G_tTYc4Q";
const String password = "EYFN4VYi";
const String host = "192.168.1.2";                      //IP of web server            

void setup() {
  Serial.begin(9600);

  //RELAY PIN
  pinMode(D4, OUTPUT);
  
  //BUZZER PIN
  pinMode(D5, OUTPUT);
  
  //SETUP DHT
  dht.setup(D8, DHTesp::DHT11);

  //SETUP RGB LED
  pinMode(D11, OUTPUT);
  pinMode(D10, OUTPUT);
  pinMode(D9, OUTPUT);
  
  //CONNECT TO WIFI
  Serial.println("Connecting to " + SSID);
  WiFi.mode(WIFI_STA);
  WiFi.begin(SSID, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  
  Serial.println("WiFi connected");
  Serial.println("IP address: ");      
  Serial.println( WiFi.localIP());                      //IP of your WEMOS
}

int delayTime = 1000*60*5; 

void loop() {           
  if(WiFi.status()== WL_CONNECTED){
    check_humidity_value();
    if(delayTime >= 1000*60*5){
      store_temp_and_humidity(get_temperature(), get_humidity());
      delayTime = 0;
    }
    delay(500);
    delayTime += 500;
  }
  else{
    Serial.println("Error in WiFi connection");
  }
}

void check_humidity_value(){
  //update current temp on database
  update_current_humidity(get_humidity());
  
  //connect to web service
  String sql_statement = "http://" + host + "/HumidityMonitoring/WebService/getDehumidifierStatus.php";
  http.begin(wifiClient, sql_statement);
  http.addHeader("Content-Type", "text/plain");

  //get reply from web service
  http.GET();
  String dehumidifierStatus = http.getString();

  //set humidifier status
  if (dehumidifierStatus == "LOW"){
    low_humidity();  
  }
  else if (dehumidifierStatus == "HIGH") {
    high_humidity();
  }
  else {
    normal_humidity();
  }
}

void update_current_humidity(String hum){
  String sql_statement = "http://" + host + "//HumidityMonitoring/WebService/updateCurrenthumidity.php?ipsrc=1&hum=" + hum;
  http.begin(wifiClient, sql_statement);
  http.addHeader("Content-Type", "text/plain");
  http.GET();
  String response = http.getString();
  Serial.println(response);
}

void store_temp_and_humidity(String temp, String hum){
  String sql_statement = "http://" + host + "/HumidityMonitoring/WebService/recordReading.php?ipsrc=1&hum=" + hum + "&temp=" + temp;
  http.begin(wifiClient, sql_statement);
  http.addHeader("Content-Type", "text/plain");
  http.GET();
  String response = http.getString();
  Serial.println(response);
}

void low_humidity(){
  //TRIGGER RELAY
  digitalWrite(D4, LOW);

  //DISPLAY GREEN
  analogWrite(D11, 0);
  analogWrite(D10, 0);
  analogWrite(D9, 255);

  //TRIGGER BUZZER
  tone(D5,261);
  delay(500);
  delayTime += 500;
  noTone(D5);
}

void normal_humidity(){
  //TRIGGER RELAY
  digitalWrite(D4, HIGH);

  //DISPLAY GREEN
  analogWrite(D11, 0);
  analogWrite(D10, 255);
  analogWrite(D9, 0);
}

void high_humidity(){
  //TRIGGER RELAY
  digitalWrite(D4, HIGH);

  //DISPLAY RED
  analogWrite(D11, 255);
  analogWrite(D10, 0);
  analogWrite(D9, 0);

  //TRIGGER BUZZER
  tone(D5,261);
  delay(500);
  delayTime += 500;
  noTone(D5);
}

String get_temperature(){
  float temperature = dht.getTemperature();
  
  char string_temp[5];
  sprintf(string_temp, "%g", temperature);

  return string_temp;
}

String get_humidity(){
  float humidity = dht.getHumidity();
  
  char string_hum[5];
  sprintf(string_hum, "%g", humidity);

  return string_hum;
}
