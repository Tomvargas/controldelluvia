import serial #Librería para leer datos de arduino (Monitor serial)
import pymysql.cursors
from datetime import date
import datetime

'''
Salidas de arduino
1- estado de lluvia (0 No, 1 Si)
2- Ph de la lluvia (porcentaje / 25 si no hay lluvia)
3- Estado del techo ( 0 Abierto, 1 cerrado )
'''

try:
    #Realizar la conexion
    print("======== CONFIGURACIÓN DEL MÓDULO ========\n")
    puerto = 'COM3' #input("Ingrese El puerto donde se conectó su módulo Ejemplo COM3 >> ")
    nbegin = 9600 #input("Ingrese el numero de taza de transferencia Ejemplo 9600 >> ")
    #print("taza de transferencia: "+nbegin) 
    
    arduino = serial.Serial(port=puerto, baudrate=int(nbegin))
    
    print("Conectado a la placa por medio del puerto <"+puerto+">...")    

    try:
        print("======== OBTENIENDO INFORMACION DE LA BASE DE DATOS ========\n")
        host = "localhost" #input("Ingrese su host: ")   
        userdb = "root" #input("Usuario con acceso a su base de datos: ")
        userpwd = "" #input("Contraseña de su usuario de BD: ")
        database = "controllluvia" #input("Nombre de su base de datos: ")
        # Connect to the database
        lista = []
        contador=0

        print("Conectando la BD "+database+"...") 

        #bucle infinito leyendo los datos
        try:
            while True:
                line = arduino.readline()   # Obtiene una linea de la salida de arduino
                conversion = str(line.decode('ascii', errors='replace')) #Adorna la cadena para visualizarla bien
                conversion = conversion.strip('\n')
                conversion = conversion.strip('\r')
                if conversion != '':
                    lista.append(conversion)
                
                if len(lista) == contador+3:

                    connection = pymysql.connect(host=host,
                                    user=userdb,
                                    password=userpwd,
                                    database=database,
                                    cursorclass=pymysql.cursors.DictCursor)

                    with connection:
                        with connection.cursor() as cursor:
                            # Create a new record                            
                            today = date.today()
                            dateserver = today.strftime("%Y/%m/%d")
                            Now = datetime.datetime.now()
                            hourserver = '{:02d}'.format(Now.hour)
                            sql = "INSERT INTO `registro` (`REG_FECHA`, `REG_LLUVIA`, `REG_PH`, `REG_TECHO`) VALUES ('{0}', {1}, {2}, '{3}')".format(dateserver, int(lista[contador]), float(lista[contador+1]), int(lista[contador+2]))
                            
                            cursor.execute(sql)
                            connection.commit()                       

                    print("Se ha agregado un registro")
                    contador+=3

        except Exception as e:
            input("Error inesperado\n"+ str(e))
    except Exception as e:
        input("Error con la conexión de la base de datos...\n"+str(e))
   
except Exception as e:
    input("No se pudo establecer conexión con su módulo de sensores...\n"+str(e)) #Si se produce un error, la conexion con arduino fallo
