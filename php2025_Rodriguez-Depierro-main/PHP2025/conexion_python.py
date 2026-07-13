import mysql.connector
from mysql.connector import Error


def obtener_conexion():
    """Crea y devuelve una conexion a MySQL."""
    try:
        conexion = mysql.connector.connect(
            host="localhost",
            user="german",
            password="german1234",
            database="curso_php2025",
        )

        if conexion.is_connected():
            return conexion

    except Error as e:
        print(f"Error al conectar a MySQL: {e}")
        return None


if __name__ == "__main__":
    conn = obtener_conexion()

    if conn:
        print("Conexion exitosa a la base de datos.")
        conn.close()
        print("Conexion cerrada.")
    else:
        print("No se pudo establecer la conexion.")
