<?php

namespace BackendBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use BackendBundle\Entity\Slider;

class SliderRepository extends DashboardRepository
{
	public function getSliderBySystemName(string $name)
	{
		return $this->createQueryBuilder('q')
			->where('q.systemName=:name')
			->andWhere('q.showOnWebsite=:showOnWebsite')
			->setParameters([
				'name' => $name,
				'showOnWebsite' => Slider::YES
			])
			->getQuery()
			->getOneOrNullResult();
	}
}
