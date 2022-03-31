/*
 * 
 * PROYECTO CONTROL DE LLUVIAS ÁCIDAS
 *  CONFIGURACIÓN DEL SENSOR DE PH --------------------------------------------------------------------------------------------
 *    REPOSITORIO OFICIAL: https://github.com/DFRobot/DFRobot_PH
 *    NOTA: CONSIDERAR EL USO DEL SENSOR DE TEMPERATURA DS18B20
 *    COMANDOS PATA CALIBRACIÓN DEL SENSOR:
 *      NOTA: CON EL PROGRAMA SUBIDO A LA PLACA ABRIR EL MONITOR SERIAL Y ENVIAR LOS COMANDOS
 *      - enterph -> entrar al modo de calibración de PH
 *      - calph   -> calibre con la solución buffer estándar, se reconocerán automáticamente dos soluciones buffer (4.0 y 7.0)
 *      - exitph  -> guardar los valores y salir del modo calibración
 *  CONFIGURACIÓN DEL SENSOR DE TEMPERATURA
 *    - INSTALAR LA LIBRERIA CORRESPONDIENTE
 *      - OPCIÓN SKETCH > INCLUDE LIBRARY > MANAGE LIBRARIES
 *      - EN LA BARRA DE ENTRADA ESCRIBIR ds18b20
 *      - BUSCAR LA LIBRERIA DallasTemperature by Miles Burton
 *      - INSTALAR
 *      - ESCRIBIR onewire Y BUSCAR LA LIBRERIA
 *      - INSTALAR
 * 
 */

#include <DFRobot_PH.h>
#include <OneWire.h>
#include <DallasTemperature.h>

int looptime = 10000; // TIEMPO QUE TARDA EN OBTENER DATOS ( 10 SEGUNDOS )

// ------------------------------------------SENSOR DE LLUVIA
#define sensor_DO A0

// ------------------------------------------SENSOR DE PH
#define PH_PIN A1
float  voltagePH, phValue, temperature = 25;
DFRobot_PH ph;

//------------------------------------------ SENSOR DE TEMPERATURA
#define ONE_WIRE_BUS 9 //el cable de datos está en el pin 9 
// Setup a oneWire instance to communicate with any OneWire device
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

// ------------------------------------------MOTORES
int timeOC = 5000; //tiempo que tarda en desplegar y enrollar todo el techo 5s
//MOTOR 1
int enA = 2;
int in1 = 4;
int in2 = 5;

//MOTOR 2
int enB = 3;
int in3 = 6;
int in4 = 7;

// -------------------------------------------TECHO
boolean techo = true; // el techo está enrrollado

void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);

  //motor 1
  pinMode(enA, OUTPUT);
  pinMode(in1, OUTPUT);
  pinMode(in2, OUTPUT);

  //motor 2
  pinMode(enB, OUTPUT);
  pinMode(in3, OUTPUT);
  pinMode(in4, OUTPUT);

  //sensor de PH
  ph.begin();

  //sensor de temperatura
  sensors.begin();

}
float ValorPh;
int ValorLluvia, techoValue;


void loop() {

  //---------------------------------------------------------------------------------------- SENSOR DE PH
  char cmd[10];
  static unsigned long timepoint = millis();
  if (millis() - timepoint > 1000U) {                      //intervalo de 1s
    timepoint = millis();
    temperature = readTemperature();                   // habilitar si está conectado el sensor de temperatura DS18B20
    voltagePH = analogRead(PH_PIN) / 1024.0 * 5000;      // leer el voltaje que recibe el sensor de PH
    phValue    = ph.readPH(voltagePH, temperature);      // Convertir el voltaje en niveles de ph con la compensación de la temperatura
    ValorPh = phValue;
    //Serial.print("pH:");
    //Serial.print(phValue, 2);                            // Imprimir el valor de PH
  }
  if (readSerial(cmd)) {
    strupr(cmd);
    if (strstr(cmd, "PH")) {
      ph.calibration(voltagePH, temperature, cmd);     //Proceso de calibración del sensor de ph (lee uno de los comandos arriba comentado)
    }
  }

  //---------------------------------------------------------------------------------------- VALIDAR LLUVIA
  int val = digitalRead(sensor_DO);
  if (val == 1) {
    //Serial.println("0");
    ValorLluvia = 0;
  } else {
    //Serial.println("1");
    ValorLluvia = 1;
  }

  //---------------------------------------------------------------------------------------- VALIDAR TECHO
  if (!techo) {
    //Serial.println (0);
    techoValue = 0;
    if ( ValorLluvia == 1 ) {
      if ( ValorPh < 5.0 || ValorPh > 6.5){ //si el ph baja de 5 o supera los 6.5 el techo cubre el cultivo
          abrirTecho();
          techo = true;
        } 
    }
  }
  else {
    //Serial.println (1);
    techoValue = 1;
    if ( ValorLluvia == 0 ) {
      cerrarTecho();
      techo = false;
    }
    else {
      if ( ValorPh >= 5.0 || ValorPh <= 6.5 ) { //Si el ph se mantiene entre 5 y 6.5 y está lloviendo el techo si está cubriendo se enrrolla
        cerrarTecho();
        techo = false;
      }
    }
  }

  
  Serial.println(ValorLluvia);
  Serial.println(ValorPh);
  Serial.println(techoValue);

  // Esperamos un tiempo para repetir el loop
  delay(looptime);

}

void cerrarTecho() {
  digitalWrite(in3, HIGH);
  digitalWrite(in4, LOW);
  analogWrite(enB, 255);
  delay(timeOC);
  digitalWrite(in3, LOW);
  digitalWrite(in4, LOW);
}

void abrirTecho() {
  digitalWrite(in1, HIGH);
  digitalWrite(in2, LOW);
  analogWrite(enA, 255);
  delay(timeOC);
  digitalWrite(in1, LOW);
  digitalWrite(in2, LOW);
}

int i = 0;
bool readSerial(char result[]){
    while(Serial.available() > 0){
        char inChar = Serial.read();
        if(inChar == '\n'){
             result[i] = '\0';
             Serial.flush();
             i=0;
             return true;
        }
        if(inChar != '\r'){
             result[i] = inChar;
             i++;
        }
        delay(1);
    }
    return false;
}

float readTemperature()
{
  // Send the command to get temperatures
  sensors.requestTemperatures(); 

  //GET the temperature in Celsius
  float tmpC = sensors.getTempCByIndex(0);

  return tmpC;
}
