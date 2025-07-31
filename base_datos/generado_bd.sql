/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     25/7/2025 17:06:10                           */
/*==============================================================*/


drop table if exists cliente;

drop table if exists cuenta;

drop table if exists empleado;

drop table if exists perfiles;

drop table if exists prestamo;

drop table if exists sucursal;

drop table if exists tarjeta_credito;

drop table if exists transaccion;

drop table if exists usuarios;

/*==============================================================*/
/* Table: cliente                                               */
/*==============================================================*/
create table cliente
(
   id_cliente           int(11) not null,
   nombre               varchar(100) not null,
   direccion            varchar(255) default NULL,
   telefono             varchar(20) default NULL,
   email                varchar(100) default NULL,
   primary key (id_cliente)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*==============================================================*/
/* Table: cuenta                                                */
/*==============================================================*/
create table cuenta
(
   id_cuenta            int(11) not null,
   numero_cuenta        varchar(20) not null,
   tipo                 enum('Ahorro','Corriente') not null,
   saldo                decimal(12,2) not null default 0.00,
   fecha_apertura       date not null default curdate(),
   id_cliente           int(11) not null,
   id_sucursal          int(11) not null,
   primary key (id_cuenta)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*==============================================================*/
/* Table: empleado                                              */
/*==============================================================*/
create table empleado
(
   id_empleado          int(11) not null,
   nombre               varchar(100) not null,
   puesto               enum('Cajero','Asesor','Gerente','Administrador') not null,
   telefono             varchar(20) default NULL,
   email                varchar(100) default NULL,
   id_sucursal          int(11) not null,
   primary key (id_empleado)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*==============================================================*/
/* Table: perfiles                                              */
/*==============================================================*/
create table perfiles
(
   id                   int(11) not null,
   nombre_perfil        varchar(50) not null,
   primary key (id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*==============================================================*/
/* Table: prestamo                                              */
/*==============================================================*/
create table prestamo
(
   id_prestamo          int(11) not null,
   monto                decimal(10,2) not null,
   interes              decimal(5,2) not null,
   plazo                int(11) not null,
   fecha_inicio         date not null default curdate(),
   estado               enum('Activo','Cancelado','En mora') default 'Activo',
   id_cliente           int(11) not null,
   id_sucursal          int(11) not null,
   primary key (id_prestamo)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*==============================================================*/
/* Table: sucursal                                              */
/*==============================================================*/
create table sucursal
(
   id_sucursal          int(11) not null,
   nombre               varchar(100) not null,
   direccion            varchar(255) default NULL,
   telefono             varchar(20) default NULL,
   primary key (id_sucursal)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*==============================================================*/
/* Table: tarjeta_credito                                       */
/*==============================================================*/
create table tarjeta_credito
(
   id_tarjeta           int(11) not null,
   numero_tarjeta       varchar(20) not null,
   tipo                 enum('Visa','MasterCard','AmericanExpress') not null,
   limite_credito       decimal(10,2) not null,
   fecha_emision        date not null default curdate(),
   id_cuenta            int(11) not null,
   primary key (id_tarjeta)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*==============================================================*/
/* Table: transaccion                                           */
/*==============================================================*/
create table transaccion
(
   id_transaccion       int(11) not null,
   fecha                datetime not null default CURRENT_TIMESTAMP,
   monto                decimal(10,2) not null,
   tipo                 enum('Depósito','Retiro','Transferencia') not null,
   descripcion          text default NULL,
   id_cuenta            int(11) not null,
   primary key (id_transaccion)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*==============================================================*/
/* Table: usuarios                                              */
/*==============================================================*/
create table usuarios
(
   id                   int(11) not null,
   username             varchar(50) not null,
   password             varchar(255) not null,
   perfil_id            int(11) default NULL,
   primary key (id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

alter table cuenta add constraint FK_Reference_7 foreign key (id_sucursal)
      references sucursal (id_sucursal) on delete restrict on update restrict;

alter table cuenta add constraint cuenta_ibfk_1 foreign key (id_cliente)
      references cliente (id_cliente);

alter table empleado add constraint empleado_ibfk_1 foreign key (id_sucursal)
      references sucursal (id_sucursal);

alter table prestamo add constraint FK_Reference_8 foreign key (id_sucursal)
      references sucursal (id_sucursal) on delete restrict on update restrict;

alter table prestamo add constraint prestamo_ibfk_1 foreign key (id_cliente)
      references cliente (id_cliente);

alter table tarjeta_credito add constraint tarjeta_credito_ibfk_1 foreign key (id_cuenta)
      references cuenta (id_cuenta);

alter table transaccion add constraint transaccion_ibfk_1 foreign key (id_cuenta)
      references cuenta (id_cuenta);

alter table usuarios add constraint usuarios_ibfk_1 foreign key (perfil_id)
      references perfiles (id);

