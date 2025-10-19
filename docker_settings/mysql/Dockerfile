FROM mysql:5.7

#RUN yum update -y && yum install -y \
#    vim \
#    iputils \
#    net-tools \
#    bind-utils \
#    openssh-server \
#    tree \
#    mycli \
#    less \
#    unzip \
#    dnsutils \
#    && yum clean all

# コンテナ内の/etc/mysql/ディレクトリにmy.cnfをコピー
COPY my.cnf /etc/mysql/my.cnf
COPY bashrc /root/.bashrc

EXPOSE 6342

