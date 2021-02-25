
CREATE TABLE usuarios_nom (
  id         serial NOT NULL,
  usuario    varchar(50),
  clave      text,
  cedulas    integer,
  nombres    varchar(50),
  apellidos  varchar(50),
  correo     varchar(100),
  pregunta   varchar(100),
  respuesta  text,
  fecha      timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
  log      integer NOT NULL DEFAULT 0,
  /* Keys */
  CONSTRAINT usuarios_nom_pkey
    PRIMARY KEY (id)
);
ALTER TABLE public.usuarios_nom
  ADD COLUMN foto text;
  
CREATE INDEX usuarios_nom_index01
  ON usuarios_nom
  (usuario, cedulas);

ALTER TABLE usuarios_nom
  OWNER TO da_admin;