import { createRouter, createWebHistory } from 'vue-router'
import Index from './views/Index.vue'

import LoginEmpleado from './views/Empleado/LoginEmpleado.vue'
import InicioEmpleado from './views/Empleado/InicioEmpleado.vue'
import PerfilEmpleado from './views/Empleado/PerfilEmpleado.vue'
import CuentasEmpleado from './views/Empleado/CuentasEmpleado.vue'
import ClientesEmpleado from './views/Empleado/ClientesEmpleado.vue'
import TarjetasEmpleado from './views/Empleado/TarjetasEmpleado.vue'
import TransferenciasEmpleado from './views/Empleado/TransferenciasEmpleado.vue'
import MovimientosEmpleado from './views/Empleado/MovimientosEmpleado.vue'
import GestionEmpleados from './views/Empleado/GestionEmpleados.vue'
import DetalleEmpleado from './views/Empleado/DetalleEmpleado.vue'
import EditForm from './views/Empleado/EditForm.vue'


import Login from './views/InicioCliente/Login.vue'
import InicioCliente  from './views/InicioCliente/InicioCliente.vue' 
import RegisterCliente from './views/InicioCliente/RegisterCliente.vue'
import TarjetasCliente from './views/TarjetasCliente/TarjetasCliente.vue'
import CuentasCliente from './views/CuentaCliente/CuentasCliente.vue'
import TransferenciasCliente from './views/TransferenciasCliente/TransferenciasCliente.vue'
import Contratar from './views/ContratarCliente/Contratar.vue'
import PerfilCliente from './views/PerfilCliente/PerfilCliente.vue'
import MiTarjeta from './views/TarjetasCliente/MiTarjeta.vue'


import LimitesTarjeta from './views/TarjetasCliente/LimitesTarjeta.vue'
import pinTarjeta from './views/TarjetasCliente/pinTarjeta.vue'
import detallesTarjeta from './views/TarjetasCliente/detallesTarjeta.vue'
import ContratosCliente from './views/ContratarCliente/ContratosCliente.vue'
import UpdateDNI from './views/PerfilCliente/UpdateDNI.vue'
import bajaCliente from './views/PerfilCliente/bajaCliente.vue'
import ConsultaTransferencias from './views/TransferenciasCliente/ConsultaTransferencias.vue'
import TraspasoTransferencia from './views/TransferenciasCliente/TraspasoCliente.vue'
import NuevaTarjeta from './views/ContratarCliente/NuevaTarjeta.vue'
import verCuenta from './views/CuentaCliente/verCuenta.vue'
import NuevaCuenta from './views/ContratarCliente/NuevaCuenta.vue'
import DetallesCuenta from './views/CuentaCliente/DetallesCuenta.vue'
import AhorroClientes from './views/CuentaCliente/AhorroClientes.vue'
import RecibosCliente from './views/CuentaCliente/RecibosCliente.vue'


const routes = [
  { path: '/', component: Index },
  { path: '/login-cliente', component: Login },
  { path: '/register-cliente', component: RegisterCliente },
  { path: '/login-empleado', component: LoginEmpleado },
  { path: '/inicio-empleado', component: InicioEmpleado },
  { path: '/perfil-empleado', component: PerfilEmpleado },
  { path: '/cuentas-empleado', component: CuentasEmpleado },
  { path: '/clientes-empleado', component: ClientesEmpleado },
  { path: '/tarjetas-empleado', component: TarjetasEmpleado },
  { path: '/transferencias-empleado', component: TransferenciasEmpleado },
  { path: '/movimientos-empleado', component: MovimientosEmpleado },
  { path: '/gestion-empleado', component: GestionEmpleados },
  { path: '/detalle-empleado', component: DetalleEmpleado, name: 'detalle-empleado', 
            props: route => ({
            identificador: route.query.identificador,
            tableName: route.query.tableName,
            datos: route.query.datos ? JSON.parse(route.query.datos) : null,
          })
  },

  { path: '/inicio-cliente', component: InicioCliente},
  { path: '/tarjetas-cliente', component: TarjetasCliente},
  { path: '/transferencias-cliente', component: TransferenciasCliente},
  { path: '/contratar-cliente', component: Contratar},
  { path: '/perfil-cliente', component: PerfilCliente},
  { path: '/cuentas-cliente', component: CuentasCliente},
  { path: '/miTarjeta', component: MiTarjeta},
  { path: '/edit/:table/:id', component: EditForm },
  { path: '/limitesTarjeta', component: LimitesTarjeta },
  { path: '/consultarPIN', component: pinTarjeta },
  { path: '/detallesTarjeta', component: detallesTarjeta },
  { path: '/contratos-cliente', component: ContratosCliente },
  { path: '/updateDNI', component: UpdateDNI },
  { path: '/bajaCliente', component: bajaCliente },
  { path: '/consulta-Transferencias', component: ConsultaTransferencias },
  { path: '/traspaso', component: TraspasoTransferencia },
  { path: '/nuevaTarjeta', component: NuevaTarjeta },
  { path: '/verCuenta', name: 'verCuenta', component: verCuenta },
  { path: '/nuevaCuenta', component: NuevaCuenta },
  { path: '/detallesCuenta', name:'detallesCuenta', component: DetallesCuenta },
  { path: '/ahorroClientes', component: AhorroClientes },
  { path: '/recibosCliente', name:'recibosCliente', component: RecibosCliente }

]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// GESTION DE PERMISOS

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

router.beforeEach((to, from, next) => {
  const dni = getCookie("DNI");
  const dni_empleado = getCookie("DNI_empleado");
  const rol = getCookie("Rol");

  const empleadoPaths = [
    '/inicio-empleado', '/perfil-empleado', '/cuentas-empleado',
    '/clientes-empleado', '/tarjetas-empleado', '/transferencias-empleado',
    '/movimientos-empleado', '/gestion-empleado', '/detalle-empleado'
  ];

  const clientePaths = [
    '/inicio-cliente', '/tarjetas-cliente', '/transferencias-cliente',
    '/contratar-cliente', '/perfil-cliente', '/cuentas-cliente',
    '/miTarjeta', '/limitesTarjeta', '/consultarPIN', '/detallesTarjeta',
    '/contratos-cliente', '/updateDNI', '/bajaCliente',
    '/consulta-Transferencias', '/traspaso', '/nuevaTarjeta',
    '/verCuenta', '/nuevaCuenta', '/detallesCuenta', '/ahorroClientes', '/recibosCliente'
  ];


  // PERMISOS EMPLEADO 
  if (empleadoPaths.includes(to.path) && (!dni_empleado || !rol)) {
    alert('No has iniciado sesión');
    return next('/login-empleado');
  }

  // PERMISOS ADMINISTRADOR
  if (to.path === '/gestion-empleado' && rol !== 'Administrador') {
    alert("No tienes permisos para acceder a esta sección.");
    return next('/inicio-empleado');
  }

  // PERMISOS CLIENTE
  if (clientePaths.includes(to.path) && !dni) {
    alert('No has iniciado sesión');
    return next('/login-cliente');
  }

  next(); 
});


export default router
