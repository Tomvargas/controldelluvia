import pymysql.cursors
from datetime import date
import datetime

print("======== CONFIGURACIÓN DE LA BASE DE DATOS ========\n")
host = "localhost" #input("Ingrese su host: ")   
userdb = "root" #input("Usuario con acceso a su base de datos: ")
userpwd = "" #input("Contraseña de su usuario de BD: ")
database = "controllluvia" #input("Nombre de su base de datos: ")

input("configuracion realizada")

connection = pymysql.connect(host=host,
    user=userdb,
    password=userpwd,
    database=database,
    cursorclass=pymysql.cursors.DictCursor)

Now = datetime.datetime.now()
input("conneccion exitosa"+str('{:02d}'.format(Now.hour)))
hourserver = '{:02d}'.format(Now.hour)
if hourserver == "12" :
    print("es str")

with connection:
    with connection.cursor() as cursor:
        # Create a new record                            
        today = date.today()
        dateserver = today.strftime("%Y/%m/%d")
        print(dateserver)
        input(" - fecha obtenida continuar ...")
        sql = "INSERT INTO `registro` (`REG_FECHA`, `REG_LLUVIA`, `REG_PH`, `REG_TECHO`) VALUES ('{0}', {1}, {2}, '{3}')".format(dateserver, int(1), float(7.6), int(1))
        cursor.execute(sql)
        connection.commit()

input("script completo...")