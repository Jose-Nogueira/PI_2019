#include <PID_v1.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>
#include <EEPROM.h>
#include "Adafruit_MQTT.h"
#include "Adafruit_MQTT_Client.h"

#ifndef STASSID
#define STASSID "sensor_unit"
#define STAPSK  "12341234"
#define STANAME  "ESP_lum"
#endif

#define OFF_    0
#define RED_    1
#define GREEN_  2
#define BLUE_   3

String AIO_SERVER      ="192.168.1.206";
uint16_t AIO_SERVERPORT  =1883;
String AIO_USERNAME    ="mosquitto";
String AIO_KEY         ="1234";
#define aaa "mosquitto"

int    ID = -1;
String ssid =     STASSID;
String password = STAPSK;
String host_name = STANAME;

bool configure = false;
IPAddress apIP(192, 168, 1, 1);
ESP8266WebServer server(80);
WiFiClient client;

Adafruit_MQTT_Client* mqtt;
//Adafruit_MQTT_Client mqtt(&client, AIO_SERVER, AIO_SERVERPORT, AIO_USERNAME, AIO_KEY);
Adafruit_MQTT_Publish* out_vals;
Adafruit_MQTT_Subscribe* gold;
String pub_1 = "";
String sub_1 = "";

void MQTT_connect();
void MQTT_();

//interface
int red       = 15;
int red_on    = 200;
int blue      = 13;
int blue_on   = 150;
int green     = 12;
int green_on  = 100;
int sensorPin = A0;
int push_bt   = 4;
//
//PID & controler (PI)
double Setpoint   = 850;
double PID_out    = 0;
double PID_in     = 0;
double Kp=0.1, Ki=0.2, Kd=0;
//double Kp=0.8, Ki=2, Kd=0.02;
PID myPID(&PID_in, &PID_out, &Setpoint, Kp, Ki, Kd, DIRECT);
int on_off_treshold = 127;
int aux_controler_time = 0;
int aux_controler_time_2 = 0;
//

void setup () {
  EEPROM.begin(512);
  //
  //Serial configuration
  Serial.begin(9600);
  Serial.println();
  //
  pinMode(push_bt, INPUT);
  pinMode(red, OUTPUT);
  pinMode(green, OUTPUT);
  pinMode(blue, OUTPUT);
  led(RED_);
  if((EEPROM.read(0) == 1) && (digitalRead(push_bt) == HIGH)){
      ssid = "";
      password = "";
      host_name = "";
      AIO_SERVER = "";
      int s_1 = EEPROM.read(1);
      int s_2 = EEPROM.read(2);
      int s_3 = EEPROM.read(3);
      for (int i = 0; i < s_1; i++){
          char a = EEPROM.read(i+4);
          ssid += a;
      }
      for (int i = 0; i < s_2; i++){
          char a = EEPROM.read(i+4+s_1);
          password += a;
      }
      for (int i = 0; i < s_3; i++){
          char a = EEPROM.read(i+4+s_1+s_2);
          host_name += a;
      }
      int ip_index = s_3+4+s_1+s_2;
      for (int i=0; i<4;i++){
        char a = EEPROM.read(i+ip_index);
          AIO_SERVER += (int)a;
          if(i<3)
            AIO_SERVER += ".";
      }
      AIO_SERVERPORT = 0;
      AIO_SERVERPORT = EEPROM.read(ip_index+4) << 4;
      AIO_SERVERPORT = AIO_SERVERPORT | EEPROM.read(ip_index+5);
      int s_4 = EEPROM.read(ip_index+6);//user_size
      int s_5 = EEPROM.read(ip_index+7);//pass_size
      int index = ip_index+8;
      AIO_USERNAME = "";
      AIO_KEY = "";
      for (int i = 0; i < s_4; i++){
          char a = EEPROM.read(i+index);
          AIO_USERNAME += a;
      }
      for (int i = 0; i < s_5; i++){
          char a = EEPROM.read(i+index+s_4);
          AIO_KEY += a;
      }
      ID = 0;
      ID = EEPROM.read(index+s_4+s_5) << 4;
      ID = ID | EEPROM.read(index+s_4+s_5+1);
      Serial.print("SSID: ");
      Serial.println(ssid);
      Serial.print("Pass: ");
      Serial.println(password);
      Serial.print("Name: ");
      Serial.println(host_name);
      Serial.print("MQTT_IP: ");
      Serial.println(AIO_SERVER);
      Serial.print("MQTT_port: ");
      Serial.println(AIO_SERVERPORT);
      Serial.print("MQTT_user: ");
      Serial.println(AIO_USERNAME);
      Serial.print("MQTT_pass: ");
      Serial.println(AIO_KEY);
      configure = true;
   }
  

  if(configure){
    //Basic configuration
    pinMode(sensorPin, INPUT);
    //PID configuration
    myPID.SetMode(AUTOMATIC);
    //
    //MQTT config
    pub_1 = AIO_USERNAME + "/vals/" + String(ID);
    sub_1 = AIO_USERNAME + "/gold/" + String(ID);
    mqtt  = new Adafruit_MQTT_Client(&client, AIO_SERVER.c_str(), AIO_SERVERPORT, AIO_USERNAME.c_str(), AIO_KEY.c_str());
    //mqtt = &mqtt_t;
    out_vals = new Adafruit_MQTT_Publish(mqtt, pub_1.c_str());
    gold = new Adafruit_MQTT_Subscribe(mqtt, sub_1.c_str());
    //
    //WiFi configuration
    WiFi.mode(WIFI_STA);
    WiFi.hostname(host_name.c_str());
    WiFi.begin(ssid.c_str(), password.c_str());
    Serial.println("");
    // Wait for connection
    while (WiFi.status() != WL_CONNECTED) {
      delay(500);
      Serial.print(".");
    }
    Serial.println("");
    Serial.print("Connected to ");
    Serial.println(ssid);
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());
    if (MDNS.begin(host_name.c_str())) {
      Serial.println("MDNS responder started");
    }
  
    mqtt->subscribe(gold);
    Serial.println("MQTT started");
    //
  }
  else{
    //Get configuration
    delay(1000);
    Serial.println();
    Serial.print("Configuring access point...");
    /* You can remove the password parameter if you want the AP to be open. */
    WiFi.mode(WIFI_AP);
    WiFi.hostname(host_name.c_str());
    WiFi.softAPConfig(apIP, apIP, IPAddress(255, 255, 255, 0));
    WiFi.softAP(ssid.c_str(), password.c_str());
  
    IPAddress myIP = WiFi.softAPIP();
    Serial.print("AP IP address: ");
    Serial.println(myIP);
    
    if (MDNS.begin(host_name.c_str())) {
      Serial.println("MDNS responder started");
    }
    
    server.on("/", config_page);
    server.begin();
    Serial.println("HTTP server started");
  }
}

void loop () {
  if(configure){
    //Controler
    if(aux_controler_time > 20){//Delay 2s = (40*50ms)
      //status controller off
      controler(false);
      aux_controler_time = 0;
      MQTT_();
    }
    //
    //Serial comunication
    serial_comunication();
    //
    //WiFi comunication
    WiFi_();
    //
    // Delay 50ms
    aux_controler_time++;
    if(digitalRead(push_bt) == HIGH)
      aux_controler_time_2 = 0;
    else
      aux_controler_time_2++;
    if(aux_controler_time_2 > 30){//3s e reset
      ESP.restart();
    }
    delay(50);
    //
  }
  else{
    server.handleClient();
    led(BLUE_);
    delay(100);
  }
}

void WiFi_(){
  if (WiFi.status() == WL_CONNECTED)
  {
      led(GREEN_);
  }else if ((WiFi.status() == WL_CONNECTION_LOST) || (WiFi.status() == WL_DISCONNECTED) || (WiFi.status() == WL_NO_SSID_AVAIL))
  {
      led(RED_);
  }
}

void serial_comunication(){
  //read PID new gold value ex.:"g600"
  if (Serial.available() > 0) {
    int i = Serial.parseInt();
    if(i > 0)
      Setpoint = constrain(i, 0, 1024);
  }
  //
  // send values:
  //Serial.print(Setpoint);
  //Serial.print(" ");
  //Serial.print(PID_out);
  //Serial.print(" ");
  PID_in  = analogRead(sensorPin);
  //Serial.println(PID_in);
  //
}
void controler(bool status_on){
  PID_in  = analogRead(sensorPin);
  myPID.Compute();

  //on/off controler with PID signal stabilized
  if(status_on){
    if(PID_out > red_on){
      digitalWrite(red, HIGH);
      digitalWrite(blue, LOW);
      digitalWrite(green, LOW);
    }else if(PID_out > blue_on){
      digitalWrite(red, LOW);
      digitalWrite(blue, HIGH);
      digitalWrite(green, LOW);
    }else{
      digitalWrite(red, LOW);
      digitalWrite(blue, LOW);
      digitalWrite(green, HIGH);
     }
  }
}

void handleNotFound() {
  String message = "File Not Found\n\n";
  message += "URI: ";
  message += server.uri();
  message += "\nMethod: ";
  message += (server.method() == HTTP_GET) ? "GET" : "POST";
  message += "\nArguments: ";
  message += server.args();
  message += "\n";
  for (uint8_t i = 0; i < server.args(); i++) {
    message += " " + server.argName(i) + ": " + server.arg(i) + "\n";
  }
  server.send(404, "text/plain", message);
}
void config_page() {
  bool conf = false;
  String ssid_, pass_, name_, ip_, user_, b_pass_;
  int ip1,ip2,ip3,ip4, port_, ID_;
  String message = "<!DOCTYPE html>"
          "<html>"
          "<body>"

          "<h2>ESP8266 lum sensor</h2>";
          
  int var = 0;
  for (uint8_t i = 0; i < server.args(); i++) {
    if(server.argName(i) == "ssid"){
      ssid_ = server.arg(i);
      var = var | 0x01;
    }else if(server.argName(i) == "pass"){
      pass_ = server.arg(i);
      var = var | 0x02;
    }else if(server.argName(i) == "name"){
      name_ = server.arg(i);
      var = var | 0x04;
    }else if(server.argName(i) == "ip1"){
      ip1 = server.arg(i).toInt();
      var = var | 0x08;
    }else if(server.argName(i) == "ip2"){
      ip2 = server.arg(i).toInt();
      var = var | 0x10;
    }else if(server.argName(i) == "ip3"){
      ip3 = server.arg(i).toInt();
      var = var | 0x20;
    }else if(server.argName(i) == "ip4"){
      ip4 = server.arg(i).toInt();
      var = var | 0x40;
    }else if(server.argName(i) == "port"){
      port_ = server.arg(i).toInt();
      var = var | 0x80;
    }else if(server.argName(i) == "user"){
      user_ = server.arg(i);
      var = var | 0x100;
    }else if(server.argName(i) == "b_pass"){
      b_pass_ = server.arg(i);
      var = var | 0x200;
    }else if(server.argName(i) == "ID"){
      ID_ = server.arg(i).toInt();
      var = var | 0x400;
    }
  }
  if(var == 0x7FF){
    ip_ = String(ip1)+"."+String(ip2)+"."+String(ip3)+"."+String(ip4);
    Serial.print("SSID: ");
    Serial.println(ssid_);
    Serial.print("Pass: ");
    Serial.println(pass_);
    Serial.print("Name: ");
    Serial.println(name_);
    Serial.print("IP: ");
    Serial.println(ip_);
    Serial.print("port: ");
    Serial.println(String(port_));
    Serial.print("user: ");
    Serial.println(user_);
    Serial.print("b_pass: ");
    Serial.println(b_pass_);
    int s_1 = ssid_.length();
    int s_2 = pass_.length();
    int s_3 = name_.length();
    char char_array[s_1];
    char char_array_2[s_2];
    char char_array_3[s_3];
    ssid_.toCharArray(char_array, s_1+1);
    pass_.toCharArray(char_array_2, s_2+1);
    name_.toCharArray(char_array_3, s_3+1);
    EEPROM.write(0, 1);
    EEPROM.write(1, s_1);
    EEPROM.write(2, s_2);
    EEPROM.write(3, s_3);
    for (int i = 0; i < s_1; i++)
      EEPROM.write(i+4, char_array[i]);
    for (int i = 0; i < s_2; i++)
      EEPROM.write(i+4+s_1, char_array_2[i]);
    for (int i = 0; i < s_3; i++)
      EEPROM.write(i+4+s_1+s_2, char_array_3[i]);
      
    int ip_index = s_3+4+s_1+s_2;
    EEPROM.write(ip_index, ip1);
    EEPROM.write(ip_index+1, ip2);
    EEPROM.write(ip_index+2, ip3);
    EEPROM.write(ip_index+3, ip4);
    EEPROM.write(ip_index+4, (port_ >> 4) & 0xFF);
    EEPROM.write(ip_index+5, port_ & 0xFF);
    int s_4 = user_.length();
    int s_5 = b_pass_.length();
    char char_array_4[s_4];
    char char_array_5[s_5];
    user_.toCharArray(char_array_4, s_4+1);
    b_pass_.toCharArray(char_array_5, s_5+1);
    EEPROM.write(ip_index+6, s_4);
    EEPROM.write(ip_index+7, s_5);
    int index = ip_index+8;
    for (int i = 0; i < s_4; i++)
      EEPROM.write(i+index, char_array_4[i]);
    for (int i = 0; i < s_5; i++)
      EEPROM.write(i+index+s_4, char_array_5[i]);
    EEPROM.write(index+s_4+s_5, (ID_ >> 4) & 0xFF);
    EEPROM.write(index+s_4+s_5+1, ID_ & 0xFF);
    EEPROM.commit();
    message += "<br><h2>sucess</h2>";
    conf = true;
  }
  message = "<form>"
              "SSID:<br>"
              "<input type='text' name='ssid'>"
              "<br>"
              "Password:<br>"
              "<input type='password' name='pass'>"
              "<br>"
              "Nome:<br>"
              "<input type='text' name='name'>"
              "<br><br>"
              "Broker_IP:<br>"
              "<input type='number' name='ip1'>.<input type='number' name='ip2'>.<input type='number' name='ip3'>.<input type='number' name='ip4'>"
              "<br><br>"
              "Broker_port:<br>"
              "<input type='number' name='port'>"
              "<br><br>"
              "Broker_user:<br>"
              "<input type='text' name='user'>"
              "<br><br>"
              "Broker_password:<br>"
              "<input type='password' name='b_pass'>"
              "<br><br>"
              "ID:<br>"
              "<input type='number' name='ID'>"
              "<br><br>"
              "<input type='submit' value='Submit'>"
            "</form>" 
            
            "</body>"
            "</html>";
  server.send(200, "text/html", message);
  if(conf){
    //reset
    delay(1000);
    ESP.restart();
  }
}
void led(int color){
  switch(color){
    case 1:
      digitalWrite(red, HIGH);
      digitalWrite(blue, LOW);
      digitalWrite(green, LOW);
      break;
    case 2:
      digitalWrite(red, LOW);
      digitalWrite(blue, LOW);
      digitalWrite(green, HIGH);
      break;
    case 3:
      digitalWrite(red, LOW);
      digitalWrite(blue, HIGH);
      digitalWrite(green, LOW);
      break;
    default:
      digitalWrite(red, LOW);
      digitalWrite(blue, LOW);
      digitalWrite(green, LOW);
      break;
  }
}

void MQTT_connect() {
  int8_t ret;

  // Stop if already connected.
  Serial.print("MQTT conection check...");
  if (mqtt->connected()) {
    return;
  }
  int vv= 0;
  Serial.print("Connecting to MQTT... ");
  while ((ret = mqtt->connect()) != 0) { // connect will return 0 for connected
       Serial.println(mqtt->connectErrorString(ret));
       Serial.println("Retrying MQTT connection in 5 seconds...");
       mqtt->disconnect();
       delay(5000);
       if(vv == 1){
          led(OFF_);
          vv = 0;
       }else{
          led(BLUE_);
          vv = 1;
       }
  }
  Serial.println("MQTT Connected!");
}
void MQTT_(){
  MQTT_connect();
  
  Adafruit_MQTT_Subscribe *subscription;
  while ((subscription = mqtt->readSubscription(500))) {
    if (subscription == gold) {
      Serial.print(F("Gold: "));
      Serial.println((char *)gold->lastread);
      Setpoint = atoi((char *)gold->lastread);
    }
  }

  // Now we can publish stuff!
  Serial.print(F("\nSending vals: "));
  String message = String(Setpoint) +  ";" + String(PID_out) + ";" + String(PID_in);
  Serial.print(message);
  Serial.print("...");
  if (! out_vals->publish(message.c_str())) {
    Serial.println(F("Failed"));
  } else {
    Serial.println(F("OK!"));
  }
}
